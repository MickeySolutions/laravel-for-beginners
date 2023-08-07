<x-layout>
    <div class="container container--narrow  py-md-5 my-5">
        <h2 class="mb-3 text-center">Choose new Avatar image</h2>
        <form action="/manage-avatar" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input type="file" name="avatar"  />
                @error('avatar')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">Save</button>
        </form>
    </div>
</x-layout>
