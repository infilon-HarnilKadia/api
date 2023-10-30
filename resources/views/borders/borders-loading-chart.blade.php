@include('header')
<div class="page-content-wrapper">
    <style>
        .theme {
            color: #21437F
        }
    </style>

    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Border</li>
                    <li class="breadcrumb-item active"><a href="{{ url('broders-border-detail') }}">Loading Chart</a></li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="content-viewport">
                    <form action="{{ url('borders-border-details/save') }}" method="post">
                        @csrf
                        @if (isset($id))
                            <input type="hidden" name="id" value="{{ $id }}">
                        @endif
                        <div class="grid">
                            <p class="grid-header">Loading Chart</p>
                            <div class="grid-body">
                                
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@include('footer')

