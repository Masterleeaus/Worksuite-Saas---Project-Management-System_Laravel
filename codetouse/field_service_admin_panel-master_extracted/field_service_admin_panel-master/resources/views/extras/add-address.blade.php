<div class="d-flex justify-content-end">
    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
        data-bs-target="#modal-form">Add New Address</button>
    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body p-1">
                    <div class="card">
                        <div class="pb-0 text-center">
                            <h5 class="">Add New Address</h5>
                            <hr/>
                        </div>
                        <div class="card-body">
                            {!! Form::open([
                                'route' => 'customer.address.add',
                                'files' => true,
                            ]) !!} <div class="input-group input-group-outline">
                                <label class="form-label">Address Title</label>
                                <input type="text" name="title" class="form-control"
                                    onfocus="focused(this)" onfocusout="defocused(this)" required>
                            </div>
                            <div>Country:</div>
                            <div class="form-group">
                                <div class="input-group input-group-outline">
                                    {!! Form::label('country', ' ', ['class' => 'control-label']) !!}
                                    {!! Form::select('country', $countries->pluck('name_en', 'id'), null, [
                                        'class' => 'form-control',
                                    ]) !!}
                                    @error('country')
                                        <p style="color:red">{!! $message !!}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>Region:</div>
                            <div class="form-group">
                                <div class="input-group input-group-outline">
                                    {!! Form::label('region', ' ', ['class' => 'control-label']) !!}
                                    {!! Form::select('region', $regions->pluck('name_en', 'id'), null, [
                                        'class' => 'form-control',
                                    ]) !!}
                                    @error('region')
                                        <p style="color:red">{!! $message !!}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>City:</div>
                            <div class="form-group">
                                <div class="input-group input-group-outline">
                                    {!! Form::label('city', ' ', ['class' => 'control-label']) !!}
                                    {!! Form::select('city', $cities->pluck('name_en', 'id'), null, [
                                        'class' => 'form-control',
                                    ]) !!}
                                    @error('city')
                                        <p style="color:red">{!! $message !!}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="input-group input-group-outline" style="margin: 10px 0px 0px 0px;">
                                <label class="form-label">Location Link</label>
                                <input type="text" name="location" class="form-control"
                                    onfocus="focused(this)" onfocusout="defocused(this)" required>
                            </div>
                            <div class="input-group input-group-outline" style="margin: 10px 0px 0px 0px;">
                                <label class="form-label">Note</label>
                                <input type="text" name="location" class="form-control"
                                    onfocus="focused(this)" onfocusout="defocused(this)" >
                            </div>


                            <br />
                            {!! Form::submit('Add', ['class' => 'btn btn-outline-primary col-md-12 col-md-offset-12']) !!}

                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>