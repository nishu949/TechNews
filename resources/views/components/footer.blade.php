<footer class="bg-dark text-white-50 py-4 mt-5 border-top border-secondary">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <h5 class="text-white fw-bold">
                    <i class="fas fa-code me-2 text-primary"></i>TechNews
                </h5>
                <p class="small">
                    A developer blog and tech article publishing platform built with Laravel.
                    Share your knowledge with the developer community.
                </p>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <h6 class="text-white fw-bold">Quick Links</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                    <li><a href="{{ route('categories.index') }}" class="text-white-50 text-decoration-none">Categories</a></li>
                    <li><a href="{{ route('tags.index') }}" class="text-white-50 text-decoration-none">Tags</a></li>
                    @auth
                        @if(auth()->user()->isAuthor())
                            <li><a href="{{ route('articles.create') }}" class="text-white-50 text-decoration-none">Write Article</a></li>
                        @endif
                    @endauth
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-white fw-bold">Contact</h6>
                <p class="small">
                    <i class="fas fa-envelope me-2"></i> hello@technews.com
                </p>
            </div>
        </div>
        <hr class="border-secondary">
      
    </div>
</footer>