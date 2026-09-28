<?php

namespace App\Http\Controllers\admin;

use App\Models\Publicite;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PubliciteController extends Controller
{
    // Un seul point de vérité pour les types valides, utilisé par la validation
    // et par les pages de gestion dédiées (une page par type au lieu d'un
    // formulaire générique avec un <select> qui affichait/cachait des champs en JS).
    public const TYPES = ['slider', 'arriere-plan', 'top-promo', 'annonce'];

    // Clés de cache (HomePageController + le view composer "site.*"/"admin.*" de
    // AppServiceProvider) qui contiennent des données issues de Publicite : à vider
    // après chaque création/modification/suppression/changement d'état, sinon un
    // changement peut mettre jusqu'à 10 min à apparaître sur le site (cause du souci
    // "quand je modifie, ça ne prend pas sur l'accueil").
    private const CACHE_KEYS = ['sliders_active', 'background_active', 'top_promo_active', 'annonce_active'];

    private function clearCache(): void
    {
        foreach (self::CACHE_KEYS as $key) {
            Cache::forget($key);
        }
    }

    // Champs réellement exploités par chaque type côté site (vérifié dans les vues
    // qui consomment chaque type) : sert à n'afficher, dans le formulaire dédié à
    // un type, que les champs qui ont un effet visible.
    public const CHAMPS_PAR_TYPE = [
        'slider'       => ['image', 'texte', 'lien'],
        'arriere-plan' => ['image'],
        'top-promo'    => ['image', 'texte', 'lien', 'discount', 'dates'],
        'annonce'      => ['texte', 'dates'],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $type = request('type');
        $publicite = Publicite::orderBy('created_at', 'DESC')
            ->when($type, fn ($q) => $q->whereType($type))
            ->get();
        return view('admin.pages.publicite.index', compact('publicite'));
    }

    /**
     * Page de gestion dédiée à un seul type (liste + formulaire propre à ce type).
     */
    public function manage(string $type)
    {
        abort_unless(in_array($type, self::TYPES, true), 404);

        $publicite = Publicite::where('type', $type)
            ->orderBy('created_at', 'DESC')
            ->get();

        $champs = self::CHAMPS_PAR_TYPE[$type];

        return view('admin.pages.publicite.manage', compact('type', 'publicite', 'champs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => ['required', Rule::in(self::TYPES)],
        ]);

        $publicite = Publicite::create([
            'type' => $request['type'],
            'url' => $request['url'],
            'texte' => $request['texte'],
            'discount' => $request['discount'],
            'date_debut_pub' => $request['date_debut_pub'],
            'date_fin_pub' => $request['date_fin_pub'],
            'status_pub' => $request['status_pub'],
            'button_name' => $request['button_name'],
        ]);


        //upload category_image
        if ($request->has('image')) {
            $publicite->addMediaFromRequest('image')->toMediaCollection('publicite_image');
        }

        $this->clearCache();

        return back()->with('success', 'Nouvelle Publicite ajoutée avec success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $publicite = Publicite::findOrFail($id);
        $champs = self::CHAMPS_PAR_TYPE[$publicite->type] ?? ['image', 'texte', 'lien', 'discount', 'dates'];

        return view('admin.pages.publicite.edit', compact('publicite', 'champs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'type' => ['required', Rule::in(self::TYPES)],
        ]);

        $publicite = tap(Publicite::findOrFail($id))->update([
            'type' => $request['type'],
            'url' => $request['url'],
            'texte' => $request['texte'],
            'discount' => $request['discount'],
            'date_debut_pub' => $request['date_debut_pub'],
            'date_fin_pub' => $request['date_fin_pub'],
            'status_pub' => $request['status_pub'],
            'button_name' => $request['button_name'],
        ]);

        //upload category_image
        if ($request->has('image')) {
            $publicite->clearMediaCollection('publicite_image');
            $publicite->addMediaFromRequest('image')->toMediaCollection('publicite_image');
        }

        $this->clearCache();

        return back()->withSuccess('Publicite modifiée avec success');
    }


    //enable or desable  a publicite
    public function changeState(Request $request)
    {
        $id = $request['id'];
        $state = '';
        if ($request['state'] == 'active') {
            $state = 'desactive';
        } else {
            $state = 'active';
        }

        Publicite::where('id', $id)->update(['status' => $state]);

        $this->clearCache();

        return response()->json([
            'success' => 200,
            'state' => $state,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Publicite::whereId($id)->delete();

        $this->clearCache();

        return response()->json([
            'status' => 200
        ]);
    }
}
