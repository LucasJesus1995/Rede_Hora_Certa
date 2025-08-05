  <div class="card">
    <div class="card-heading">
      <h2>
        {{Lang::get('app.perfil-vinculo')}}
      </h2>
      <small>{{Lang::get('description.perfil-vinculo')}}</small>
    </div>
   <div class="card-tools">
        <a href="" class="btn-new-entry btn btn-default">{{Lang::get('app.novo-registro')}}</a>
    </div>
    <div class="card-body bg-light lt" id="">
        <input type="hidden" name="perfil" id="perfil" value="{{$perfil}}" />
        @if($roles)
            <table class="table table-responsive table-bordered table-condensed table-striped table-hover" >
                @foreach($roles AS $row)
                    <thead>
                        <tr role="row" class="green">
                            <th colspan="100%">{{$row['title']}}</th>
                        </tr>
                         <tr class="success">
                            <th>{!!Lang::get('grid.pagina')!!}</th>
                            <th>{!!Lang::get('grid.slug')!!}</th>
                            <th>{!!Lang::get('grid.view')!!}</th>
                            <th>{!!Lang::get('grid.list')!!}</th>
                            <th>{!!Lang::get('grid.created')!!}</th>
                            <th>{!!Lang::get('grid.delete')!!}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 1;
                        ?>
                        @if(!empty($row['roles']))
                            @foreach($row['roles'] AS $rules)
                                <?php
                                    $class = ($i == 1) ? "info" : null;
                                    $class = null;

                                    $is_view = ($rules['view'] || $perfil == 1) ? "checked='checked'" : null;
                                    $is_list = ($rules['list'] || $perfil == 1) ? "checked='checked'" : null;
                                    $is_created = ($rules['created'] || $perfil == 1) ? "checked='checked'" : null;
                                    $is_delete = ($rules['delete'] || $perfil == 1) ? "checked='checked'" : null;

                                    $disabled = ($rules['internal'] == 2) ? "disabled='disabled'" : null;
                                    $disabled_only_view = ($rules['internal'] == 3) ? "disabled='disabled'" : null;

                                    $disabled_owner = ($perfil == 1) ? "disabled='disabled'" : null;
                                ?>
                                 <tr class="grid-status-1 btn-action-perfil {{$class}}" id="ln-update-{{$rules['id']}}" data-id="{{$rules['id']}}">
                                    <td class="no-lower">{{$rules['title']}}</td>
                                    <td class="no-lower">{{$rules['slug']}}</td>
                                    <td class="align-center for-label-input" for="view-{{$rules['id']}}"><input type="checkbox"  name="view" {{$disabled_owner}} rel="{{$rules['id']}}"  id="view-{{$rules['id']}}" {{$is_view}}></td>
                                    <td class="align-center for-label-input" for="list-{{$rules['id']}}"><input type="checkbox" name="list" {{$disabled}} {{$disabled_only_view}} {{$disabled_owner}} rel="{{$rules['id']}}"  id="list-{{$rules['id']}}" {{$is_list}}></td>
                                    <td class="align-center for-label-input" for="created-{{$rules['id']}}"><input type="checkbox" name="created" {{$disabled_only_view}} {{$disabled_owner}} rel="{{$rules['id']}}"  id="created-{{$rules['id']}}" {{$is_created}}></td>
                                    <td class="align-center for-label-input" for="delete-{{$rules['id']}}"><input type="checkbox" name="delete" {{$disabled}} {{$disabled_only_view}} {{$disabled_owner}} rel="{{$rules['id']}}"  id="delete-{{$rules['id']}}" {{$is_delete}}></td>
                                 </tr>
                                 <?php
                                    $i++;
                                 ?>
                            @endforeach
                        @endif
                    </tbody>
                @endforeach
             </table>
        @endif

    </div>
  </div>