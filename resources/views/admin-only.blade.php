<x-layout>
    <div class="container py-md-5 container--narrow">
      <div class="text-center">
        <h2>Hello <strong>{{auth()->user()->username}}</strong>, you are Admin.</h2>
        <p class="lead text-muted">You are able to see this page.</p>
      </div>
    </div>
</x-layout>
