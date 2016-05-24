<div id="create_category" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">{{ trans('core.add_custom_category') }}</h6>
            </div>

            <div class="modal-body">
               @include("categories.form")
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary" @click="addCategory">{{ trans('core.add_and_new') }}</button>
                {{--<button type="button"  class="btn btn-success" @click="addCategory">{{ trans('core.add_and_close') }}</button>--}}

            </div>
        </div>
    </div>
</div>