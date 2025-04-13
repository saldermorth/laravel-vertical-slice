<!-- form.blade.php -->
<form method="POST" action="{{ route('example') }}">
    @csrf
    <!-- Your form inputs here -->
    <button type="submit">Submit</button>
</form>