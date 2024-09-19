@foreach($posts as $post)
    <h1>{{ $post['title'] }}</h1>
    <span>{{ $post['full_name'] }}</span>
    <br>
    <img src="{{ asset('storage/' . $post['image']) }}" alt="">
    <p>{{ $post['content'] }}</p>
    <hr>
@endforeach