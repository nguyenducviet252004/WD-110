<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
      <a class="navbar-brand brand-logo" href="#"><img style="width: 100px; height: 80px;" src="{{asset('assets/images/logo.png')}}" alt="logo" /></a>
      {{-- <a class="navbar-brand brand-logo-mini" href="index.html"><img src="{{asset('assets/images/logo.png')}}"
          alt="logo" /></a> --}}
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-stretch">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="mdi mdi-menu"></span>
      </button>
      <div class="search-field d-none d-md-block">

        <form class="d-flex align-items-center h-100" action="#" id="search-form">
          <div class="input-group">
              <div class="input-group-prepend bg-transparent">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>
              </div>
              <input type="text" class="form-control bg-transparent border-0" placeholder="Tìm Kiếm" id="search-input" name="query">
          </div>
          <div id="search-results" class="search-results"></div>
      </form>
      

      </div>
    </div>
</nav>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', function() {
        const query = searchInput.value;

        // Nếu không có từ khóa tìm kiếm, ẩn kết quả
        if (query.length > 0) {
            searchDatabase(query);
        } else {
            searchResults.innerHTML = '';  // Ẩn kết quả khi không có từ khóa
        }
    });

    function searchDatabase(query) {
        fetch('/search?query=' + query)
            .then(response => response.json())
            .then(data => {
                if (data.results && data.results.length > 0) {
                    displaySearchResults(data.results);
                } else {
                    searchResults.innerHTML = '<p>No results found</p>';
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function displaySearchResults(results) {
        let html = '';
        results.forEach(result => {
            html += `<div class="search-result-item">${result.name}</div>`;
        });
        searchResults.innerHTML = html;
    }
});

</script>