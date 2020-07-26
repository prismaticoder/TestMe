@extends('layouts.main')
@section('title', 'Main Dashboard')
@section('content')
    {{-- <span id="top"></span> --}}
    <div id="app" data-app="true">
        <add-question :questions="{{$questions}}" :subject="{{$subject->id}}" :class-id="{{$class_id}}">

        </add-question>
    </div>

    <br>

    @if ($mark == "nil")
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle">SET EXAM TIME AND TOTAL SCORE</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                <form data-form-type="set" class="markSubmit">
                  <div class="row">
                  <div class="col-sm-5">
                      <label for="">Exam Duration</label>
                    </div>
                    <div class="col-sm-3">
                      <input type="number" required min="0" max="5" required class="form-control hours" placeholder="Hour">
                    </div>
                    <div class="col-sm-3">
                      <input type="number" required min="0" max="59" required class="form-control minutes" placeholder="Minutes">
                    </div>
                  </div>
                  <div class="form-group row mt-3">
                    <label for="colFormLabel" class="col-sm-5 col-form-label">Total Allocatable Marks</label>
                    <div class="col-sm-4">
                      <input type="number" required min="10" max="100" class="form-control scores" id="colFormLabel" placeholder="Total Marks">
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </form>

              </div>
            </div>
          </div>
        @else
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalCenterTitle">UPDATE EXAM TIME AND TOTAL SCORE</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                    <form data-form-type="update" id="{{$mark->id}}" class="markSubmit">
                      <div class="row">
                      <div class="col-sm-5">
                          <label for="">Exam Duration</label>
                        </div>
                        <div class="col-sm-3">
                        <input type="number" required min="0" max="5" required class="form-control hours" placeholder="Hour" value="{{$mark->hours}}">
                        </div>
                        <div class="col-sm-3">
                          <input type="number" required min="0" max="59" required class="form-control minutes" placeholder="Minutes" value="{{$mark->minutes}}">
                        </div>
                      </div>
                      <div class="form-group row mt-3">
                        <label for="colFormLabel" class="col-sm-5 col-form-label">Total Allocatable Marks</label>
                        <div class="col-sm-4">
                          <input type="number" required min="10" max="100" required class="form-control scores" id="colFormLabel" placeholder="Total Marks" value="{{$mark->mark}}">
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    </div>
                </form>
                    </div>
                  </div>
                </div>
              </div>
    @endif




    <script src="{{asset('/js/app.js')}}"></script>
    @endsection



