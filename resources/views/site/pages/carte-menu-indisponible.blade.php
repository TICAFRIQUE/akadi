@extends('site.layouts.app')

@section('title', 'Carte menu non disponible')

@section('content')

<style>
.ak-cmi-section { padding: 80px 0; background: #fafafa; min-height: 50vh; }
.ak-cmi-card {
    text-align: center;
    padding: 64px 24px;
    background: #fff;
    border-radius: 16px;
    max-width: 480px;
    margin: 0 auto;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.ak-cmi-icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: rgba(248,93,5,.08);
    color: var(--ak-orange, #f85d05);
    font-size: 1.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}
.ak-cmi-card h1 { font-size: 1.2rem; font-weight: 800; color: #1a1a1a; margin-bottom: 10px; }
.ak-cmi-card p { color: #888; margin-bottom: 24px; }
</style>

<section class="ak-cmi-section">
    <div class="container">
        <div class="ak-cmi-card">
            <div class="ak-cmi-icon"><i class="fas fa-calendar-times"></i></div>
            <h1>Carte menu non disponible</h1>
            <p>Cette carte menu n'est plus accessible pour le moment. Elle a peut-être expiré ou été désactivée.</p>
            <a href="{{ route('page-acceuil') }}" class="th-btn">Retour à l'accueil</a>
        </div>
    </div>
</section>

@endsection
