<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="{{ route('dashboard') }}">Scheduler</a>
      <ul class="navbar-nav ms-auto">
        @if (!session()->has('token'))
          <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
          <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">Register</a></li>
        @else
          <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST">@csrf
              <button class="btn btn-link nav-link">Logout</button>
            </form>
          </li>
        @endif
      </ul>
    </div>
  </nav>
