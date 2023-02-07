@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>

                <div class="card-body">
                    <div class="row mb-3">
                        <label for="mgt_no" class="col-md-4 col-form-label text-md-end">{{ __('Management Number') }}</label>

                        <div class="col-md-6">
                            <label for="furigana">{{ $user->mgt_no }}</label>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="department" class="col-md-4 col-form-label text-md-end">{{ __('Department Name') }}</label>

                        <div class="col-md-6">
                            <label for="furigana">{{ $user->department }}</label>
                        </div>
                    </div>
                    </div>
                    <div class="row mb-3">
                        <label for="furigana" class="col-md-4 col-form-label text-md-end">{{ __('Furigana') }}</label>

                        <div class="col-md-6">
                            <label for="furigana">{{ $user->furigana }}</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="family_name" class="col-md-4 col-form-label text-md-end">{{ __('Family Name') }}</label>

                        <div class="col-md-6">
                            <label for="furigana">{{ $user->family_name }}</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}</label>

                        <div class="col-md-6">
                            <label for="furigana">{{ $user->first_name }}</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="region" class="col-md-4 col-form-label text-md-end">{{ __('Region Name') }}</label>

                        <div class="col-md-6">
                            <label for="furigana">{{ $user->region }}</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="official_registration_date" class="col-md-4 col-form-label text-md-end">{{ __('Date of Employment') }}</label>

                        <div class="col-md-6">
                            <label for="furigana">{{ $user->official_registration_date }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
