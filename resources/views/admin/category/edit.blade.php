@extends('admin.master')

@section('css')
<style>
ul {list-style: circle;}
li {padding: 10px 0 5px}
.arrow {cursor: pointer;user-select: none;}
.arrow::before {
    content: "\25B6"; 
    color: black; 
    display: inline-block; 
    margin-right: 6px;
}
.arrow-down::before {transform: rotate(90deg);}
.nested {display: none;}
.active {display: block;} 
.btn-delete {
    border: none; 
    margin: 0; 
    padding: 0; 
    background: none; 
    color: #d02b2b; 
    cursor: pointer;
}
.btn-delete:hover {text-decoration: underline;}
</style>
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <i class="fas fa-table"></i> Categories
    </div>
    <div class="card-body">

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-7">
            {!! $allCategory !!}
        </div>

        @canAccess('category-create')
            <div class="col-md-5">

                <form action="{{ route('admin.category.update', $category->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div id="select-boxes">
                        <div class="form-group">
                            <label for="parent_cat">Parent Category</label>
                            <select name="parent_cat_id" id="parent_cat" class="parent-cat form-control">
                                <option value="">--- Choose ---</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $cat->id == $category->parent_id ? 'selected' : '' }}>
                                        {{ $cat->name }} ({{ $cat->subCategories()->count() }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="category_name">Category Name</label>
                        <input type="text" name="category_name" value="{{ $category->name }}" id="category_name" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <input type="submit" value="Update" class="btn btn-info">
                    </div>
                </form>

            </div>
        @endcanAccess

    </div>
    
    </div>
</div>

@endsection

@section('js')
<script>

    var toggler = document.getElementsByClassName("arrow");

    for (var j = 0; j < toggler.length; j++) {
        toggler[j].addEventListener("click", function() {
            this.parentElement.querySelector(".nested").classList.toggle("active");
            this.classList.toggle("arrow-down");
        });
    }
</script>
@endsection