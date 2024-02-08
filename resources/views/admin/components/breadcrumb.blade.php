 @if (Route::currentRouteName() !== 'auth.login' && Route::currentRouteName() !== 'dashboard.index')
     <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="fas fa-tachometer-alt"></i>
                     Accueil</a></li>
             <li class="breadcrumb-item"><a href="/admin/@yield('title')"><i class=" fas fa-list"></i>
                     @yield('title')</a></li>
             <li class="breadcrumb-item active" aria-current="page"><i class="far fa-file"></i> @yield('sub-title')</li>
         </ol>
     </nav>
 @endif
