@extends('layouts.master')
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('Daily Menu For') }}<span class="label label-info" id="menu-date"> {{ $date }}</span></h3>
            <span class="pull-right">
            </span>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="box-tools">
                <a href="{{ route('daily-menus.create', ['date' => $date]) }}" class="btn btn-primary pull-right">
                    <i class=" fa fa-plus-circle"></i>
                    {{ __('Add More') }}
                </a>
            </div>
            <div class="form-group has-success">
            </div>
            <div class="form-group has-warning">
            </div>
            <div class="form-group has-error">
            </div>
            <div class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover table-bordered" id="daily-menu-table">
                            <tbody>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Food Name') }}</th>
                                    <th>{{ __('Category Name') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Updated At') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            @if (empty($menuOnDate))
                                <tr>
                                    <strong>{{ __('Nothing to show') }}</strong>
                                </tr>
                            @else
                                @foreach ($menuOnDate as $menuItem)
                                    <tr>
                                        <td>{{ $menuItem->id }}</td>
                                        <td>
                                            <a href="{{ route('foods.show', $menuItem->food->id) }}">
                                                {{ $menuItem->food->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('categories.edit', $menuItem->food->category->id) }}">
                                                {{ $menuItem->food->category->name }}
                                            </a>
                                        </td>
                                        <td>{{ number_format($menuItem->food->price, 0, ',', '.') }} {{ __('VND') }}</td>
                                        <td>{{ $menuItem->created_at }}</td>
                                        <td>{{ $menuItem->updated_at }}</td>
                                        <td class="form-control-sm">
                                            <div>
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control-sm quantity" value="{{ $menuItem->quantity }}" id="{{ $menuItem->id }}" name="quantity">
                                                    <span type="hidden" class="help-block" id="helpblock"></span>
                                                </div>
                                                <div class="col-md-6">
                                                    <button class="btn-xs btn-success btn glyphicon glyphicon-ok" name="btn-edit" value="{{ $menuItem->id }}" data-title="{{ __('Update Menu Item') }}">
                                                    </button>
                                                    <button class="btn-xs btn-warning btn glyphicon glyphicon-ban-circle" name="btn-cancel">
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn-xs btn-danger btn glyphicon glyphicon-remove" data-confirm="{{ __('Are you sure you want to delete this item?') }}" data-title="{{ __('Delete Menu Item') }}" name="btn-del" value={{ $menuItem->id }}>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            </table>
                        </div>
                        {{ $menuOnDate->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('layouts.partials.modal')
