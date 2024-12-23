<!DOCTYPE html>
<html>

<head>
    <title>Products List</title>
</head>

<body>
    <h1>Product Search</h1>

    <form method="GET" action="{{ url('/search') }}">
        <input type="text" name="query" placeholder="Search for products" required>
        <button type="submit">Search</button>
    </form>

    @if (isset($results))
        <h2>Search Results</h2>
        <ul>
            @foreach ($results as $product)
                <li>{{ $product->name }} - {{ $product->price }} - {{ $product->category }}</li>
            @endforeach
        </ul>

        <p>Execution time: {{ $execution_time }} seconds</p>
    @endif

    <!-- Button menuju ke halaman pengujian performa -->
    <br><br>
    <a href="{{ url('/performance-test') }}">
        <button>Go to Performance Test</button>
    </a>

    <h1>Products List</h1>
    <a href="{{ route('products.create') }}">Create New Product</a>
    <ul>
        @foreach($products as $product)
            <li>
                {{ $product->name }} - {{ $product->price }} - {{ $product->category }}
                <a href="{{ route('products.edit', $product->id) }}">Edit</a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>

</html>