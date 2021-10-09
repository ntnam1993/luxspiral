<!-- Modal -->
<form id="frm-delete" method="post">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <div id="modalDeleteUser" class="modal fade modals-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="exampleModalLabel"></h3>
                </div>
                <div class="modal-body">
                    <h4 class="modal-body-content"></h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal"></button>
                        </div>
                        <div class="col-sm-6 form-group col-xs-5 col-md-5">
                            <input type="hidden" name="id">
                            <button type="submit" class="btn btn-primary btn-del-ans pull-left"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>