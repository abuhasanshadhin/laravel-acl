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

                <form action="{{ route('admin.category.store') }}" id="cat-form" method="post">
                    @csrf

                    <div id="select-boxes">
                        <div class="form-group">
                            <label for="category_1">Parent Category</label>
                            <select name="parent_cat_ids[]" id="category_1" class="parent-cat form-control">
                                <option value="">--- Choose ---</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }} ({{ $category->subCategories()->count() }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="category_name">Category Name</label>
                        <input type="text" name="category_name" id="category_name" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <input type="submit" value="Submit" class="btn btn-primary">
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

    var i = 2;

    $(document).on('change', '.parent-cat', function() {
        $(this).parent().nextAll().remove();
        var parent_id = $(this).val();
        if (parent_id != '') {
            $('#select-boxes').append(`
                <div class="form-group text-center text-danger" id="sub-cat-loader">
                    <i class="fas fa-spinner fa-spin"></i> Loading...
                </div>
            `);
            var route = "{{ route('admin.sub_categories', ':id') }}";
            var _url = route.replace(':id', parent_id);
            $.ajax({
                url: _url,
                method: 'GET',
                success: function(res) {
                    $('#sub-cat-loader').remove();
                    if (res.length) {
                        var options = '<div class="form-group">';
                        options += '<label for="category_'+i+'">Sub Category</label>';
                        options += '<select name="parent_cat_ids[]" id="category_'+i+'" class="parent-cat form-control">';
                        options += '<option value="">--- Choose ---</option>';
                        for (let i = 0; i < res.length; i++) {
                            const el = res[i];
                            options += '<option value="'+el.id+'">'+el.name+'</option>';
                        }
                        options += '</select>';
                        options += '</div>';
                        $('#select-boxes').append(options);
                        i++;
                    }
                }.bind(this),
                error: function(e) {
                    $('#sub-cat-loader').remove();
                    if (e.status == 0) {
                        alert('Something went wrong :(');
                    } else {
                        alert(e.status+' | '+e.statusText);
                    }
                }
            });
        }
    });

    $(document).on('submit', '#cat-form', function (e) {
        var category_name = $('#category_name').val();
        if (category_name == '') {
            alert('The category name field is required');
            return false;
        }
    });
</script>
@endsection