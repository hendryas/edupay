<header class="mb-3">
  <nav class="navbar navbar-expand navbar-light">
      <div class="container-fluid">
          <a class="burger-btn d-block d-xl-none" href="#">
              <i class="bi bi-justify fs-3"></i>
          </a>
          <div class="ms-auto">
              <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button class="btn btn-outline-danger" type="submit">Logout</button>
              </form>
          </div>
      </div>
  </nav>
</header>
