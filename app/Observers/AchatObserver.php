<?php

namespace App\Observers;

use App\Models\Achat;
use App\Models\CategorieDepense;
use App\Models\Depense;
use App\Models\LibelleDepense;
use Illuminate\Support\Facades\Log;

class AchatObserver
{
    /**
     * Handle the Achat "created" event.
     */
    // public function created(Achat $achat): void
    // {
    //     //

    //     //les champs de la depense seront :
    //     //date_depense : date_achat
    //     //montant : somme des lignes d'achat (quantite * prix_unitaire)
    //     //description : "Dépense liée à l'achat #ID"
    //     //categorie_depense_id : "achats-stock"
    //     //libelle_depense_id : "achats-stock"
    //     //user_id : user_id de l'achat

    //     //recuperons la categorie depense achat
    //     $categorieDepense = CategorieDepense::where('slug', 'achats-stock')->first();
    //     //recuperons le libelle categorie depense achat
    //     $libelleDepense = LibelleDepense::where('slug', 'achats-stock')->first();


    //     $depense = new Depense();
    //     $depense->date_depense = $achat->date_achat;
    //     $depense->montant = $achat->lignes->sum(function ($ligne) {
    //         return $ligne->quantite * $ligne->prix_unitaire;
    //     });
    //     $depense->description = 'Dépense liée à l\'achat #' . $achat->id;
    //     $depense->categorie_depense_id = $categorieDepense->id;
    //     $depense->libelle_depense_id = $libelleDepense->id;
    //     $depense->user_id = $achat->user_id;
    //     $depense->save();

    //     //mettre dans log la création de la dépense
    //     Log::info('Dépense créée pour l\'achat', [
    //         'achat_id' => $achat->id,
    //         'depense_id' => $depense->id,
    //         'montant' => $depense->montant,
    //         'date_depense' => $depense->date_depense,
    //         'user_id' => $depense->user_id,
    //     ]);
    // }


    public function created(Achat $achat): void
    {

        //les champs de la depense seront :
        //     //date_depense : date_achat
        //     //montant : somme des lignes d'achat (quantite * prix_unitaire)
        //     //description : "Dépense liée à l'achat #ID"
        //     //categorie_depense_id : "achats-stock"
        //     //libelle_depense_id : "achats-stock"
        //     //user_id : user_id de l'achat

        //recuperons la categorie depense achat
        $categorieDepense = CategorieDepense::where('slug', 'achats-stock')->first();
        //recuperons le libelle categorie depense achat
        $libelleDepense   = LibelleDepense::where('slug', 'achats-stock')->first();

        // 👇 Sécurité si les slugs n'existent pas
        if (!$categorieDepense || !$libelleDepense) {
            Log::error('Catégorie ou libellé dépense introuvable', ['slug' => 'achats-stock']);
            return;
        }

        $depense = Depense::create([
            'date_depense'        => $achat->date_achat,
            'montant'             => $achat->montant_total, // 👈 maintenant correct
            'description'         => 'Dépense liée à l\'achat #' . $achat->id,
            'categorie_depense_id' => $categorieDepense->id,
            'libelle_depense_id'  => $libelleDepense->id,
            'user_id'             => $achat->user_id,
        ]);

        Log::info('Dépense créée pour l\'achat', [
            'achat_id'     => $achat->id,
            'depense_id'   => $depense->id,
            'montant'      => $depense->montant,
            'date_depense' => $depense->date_depense,
            'user_id'      => $depense->user_id,
        ]);
    }

    /**
     * Handle the Achat "updated" event.
     */
    public function updated(Achat $achat): void
    {
        //
    }

    /**
     * Handle the Achat "deleted" event.
     */
    public function deleted(Achat $achat): void
    {
        //
    }

    /**
     * Handle the Achat "restored" event.
     */
    public function restored(Achat $achat): void
    {
        //
    }

    /**
     * Handle the Achat "force deleted" event.
     */
    public function forceDeleted(Achat $achat): void
    {
        //
    }
}
