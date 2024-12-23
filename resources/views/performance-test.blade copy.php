<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Test</title>
</head>

<body>
    <h1>Performance Test Results</h1>

    <table border="1">
        <tr>
            <th>Input Size</th>
            <th>Execution Time (Seconds)</th>
        </tr>
        @foreach ($results as $result)
        <tr>
            <td>{{ $result['input_size'] }}</td>
            <td>{{ $result['execution_time'] }}</td>
        </tr>
        @endforeach
    </table>

    <form action="{{ url('/export-csv') }}" method="GET">
        <button type="submit">Export to CSV</button>
    </form>


    <br>
    <a href="{{ url('/products') }}">
        <button>Back to Search</button>
    </a>
</body>

</html>