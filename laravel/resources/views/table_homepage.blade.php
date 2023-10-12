<div class="container">
    <div class="button" style="padding-bottom: 20px;">
        <button type="button" class="btn btn-secondary"><a href="{{ route('clone-products') }}">Sync data product</a></button>
        <!-- <button type="button"  class="btn btn-secondary"><a href="{{ route('export.csv') }}">Export File</a></button> -->
    </div>
    <!-- <p>You are: {{ Auth::user() }}</p> -->

    @if($errors->any())
    <p style="color: red;">{{$errors->first()}}</p>
    @endif
    <div id="product-list">
    <table class="table table-bordered">
    <thead>
        <tr>
        <th scope="col">#</th>
        <th scope="col">Time sync data</th>
        <th scope="col">Type sync</th>
        <th scope="col">Vendor</th>
        <th scope="col">product_type</th>
        <th scope="col">Title</th>

        </tr>
    </thead>
    <tbody>
        @if(!$products->isEmpty())
        @foreach($products as $key => $value)
            <tr>
                <th scope="row">{{$value->id}}</th>
                <td>{{$value->time_sync}}</td>
                <td>
                @if( $value->type_sync == '1' )
                    <button class="btn btn-danger">AUTO</button>
                @else
                    <button class="btn btn-success">MANUAL</button>
                @endif
                </td>
                <td>{{$value->vendor}}</td>
                <td>{{$value->product_type}}</td>
                <td>{{$value->title}}</td>
            </tr>
        @endforeach
        @endif
    </tbody>
    </table>
    {{-- @if(!$products->isEmpty())
    <div class="d-flex justify-content-center">
    {!! $products->links() !!}
    </div>
    @endif --}}
    </div>

</div>
