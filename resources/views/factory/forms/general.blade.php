@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <a href="/home" class="btn btn-md btn-primary">Go Back</a>
    </div>
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Enter Today's Sewing Data</div>
                  <div class="card-body">
                      <form method="POST" action="/factory/sewing/data">
                          @csrf
                          <input type="hidden" name="factory_id" value="{{ Auth::user()->factory_id }}">
                          <div class="form-group row">
                              <label for="payrole" class="col-md-4 col-form-label text-md-right">Payrole</label>

                              <div class="col-md-6">
                                  <input id="payrole" type="text" placeholder="Payrole" class="form-control{{ $errors->has('payrole') ? ' is-invalid' : '' }}" name="payrole" value="{{ old('payrole') }}" required>

                                  @if ($errors->has('payrole'))
                                      <span class="invalid-feedback">
                                          <strong>{{ $errors->first('payrole') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="ppeople" class="col-md-4 col-form-label text-md-right">People on Payrole</label>
                              <div class="col-md-6">
                                  <input id="ppeople" placeholder="People on Payrole" type="number" class="form-control{{ $errors->has('ppeople') ? ' is-invalid' : '' }}" name="ppeople" value="{{ old('ppeople') }}" required>

                                  @if ($errors->has('ppeople'))
                                      <span class="invalid-feedback">
                                          <strong>{{ $errors->first('ppeople') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="cpeople" class="col-md-4 col-form-label text-md-right">People on Contract</label>

                              <div class="col-md-6">
                                  <input id="cpeople" placeholder="People on Contract" type="number" class="form-control{{ $errors->has('cpeople') ? ' is-invalid' : '' }}" name="cpeople" value="{{ old('cpeople') }}" required>

                                  @if ($errors->has('cpeople'))
                                      <span class="invalid-feedback">
                                          <strong>{{ $errors->first('cpeople') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="ocut" class="col-md-4 col-form-label text-md-right">Cutting Overtime</label>

                              <div class="col-md-6">
                                  <input id="ocut" type="number" placeholder="Cutting Overtime" class="form-control{{ $errors->has('ocut') ? ' is-invalid' : '' }}" name="ocut" value="{{ old('ocut') }}" required>

                                  @if ($errors->has('ocut'))
                                      <span class="invalid-feedback">
                                          <strong>{{ $errors->first('ocut') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="target" class="col-md-4 col-form-label text-md-right">Target Production</label>

                              <div class="col-md-6">
                                  <input id="target" type="number" placeholder="Fusing Output" class="form-control{{ $errors->has('target') ? ' is-invalid' : '' }}" name="target" value="{{ old('target') }}" required>

                                  @if ($errors->has('target'))
                                      <span class="invalid-feedback">
                                          <strong>{{ $errors->first('target') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="actual" class="col-md-4 col-form-label text-md-right">Actual Production</label>

                              <div class="col-md-6">
                                  <input id="actual" type="number" placeholder="Actual Production" class="form-control{{ $errors->has('actual') ? ' is-invalid' : '' }}" name="actual" value="{{ old('actual') }}" required>

                                  @if ($errors->has('actual'))
                                      <span class="invalid-feedback">
                                          <strong>{{ $errors->first('actual') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="outcome" class="col-md-4 col-form-label text-md-right">Pieces Sent for Finishing or Washing</label>

                              <div class="col-md-6">
                                  <input id="outcome" type="number" placeholder="People in Cutting Dept." class="form-control{{ $errors->has('outcome') ? ' is-invalid' : '' }}" name="outcome" value="{{ old('outcome') }}" required>

                                  @if ($errors->has('outcome'))
                                      <span class="invalid-feedback">
                                          <strong>{{ $errors->first('outcome') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="sam" class="col-md-4 col-form-label text-md-right">Average SAM of Garment</label>

                              <div class="col-md-6">
                                  <input id="sam" type="text" placeholder="Average SAM of Garment" class="form-control{{ $errors->has('sam') ? ' is-invalid' : '' }}" name="sam" value="{{ old('sam') }}" required>

                                  @if ($errors->has('sam'))
                                      <span class="invalid-feedback">
                                          <strong>{{ $errors->first('sam') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row mb-0">
                              <div class="col-md-6 offset-md-4">
                                  <button type="submit" class="btn btn-primary">
                                      Save Cutting Data
                                  </button>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection

@section('scripts')

@endsection
