@extends('admin.layouts.template')
@section('page_title')
    All SubCategory -SingleEcom
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Page/</span> All Sub Category</h4>
        @if (session()->has('message'))
            <div class="alert alret-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="card">
            <h5 class="card-header">Available Sub Category Information</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Id</th>
                            <th>Sub Category Name</th>
                            <th>Category</th>
                            <th>Product</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($allsubcategories as $subcategories)
                        <tr>
                            <td>{{ $subcategories->id }}</td>
                            <td>{{ $subcategories->subcategory_name }}</td>
                            <td>{{ $subcategories->category_name }}</td>
                            <td>{{ $subcategories->product_count }}</td>
                            <td>
                                <a href="{{ route('editsubcat', $subcategories->id) }}" class="btn btn-primary">Edit</a>
                                <a href="{{ route('deletesubcat', $subcategories->id) }}" class="btn btn-warning">Delete</a>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <!-- Bootstrap Table with Header - Light -->
    </div>
@endsection
