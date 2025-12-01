
<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-head />
@section('content')
<section class="auth forgot-password-page bg-base d-flex flex-wrap">
  <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
    <div class="max-w-464-px mx-auto w-100">
      <h4 class="mb-12">Reset Password</h4>
      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="email" value="{{ old('email', $email) }}">
        <div class="icon-field mb-24">
          <input type="text" name="otp" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Enter OTP" required>
        </div>
        <div class="icon-field mb-24">
          <input type="password" name="password" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="New Password" required>
        </div>
        <div class="icon-field mb-24">
          <input type="password" name="password_confirmation" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Confirm Password" required>
        </div>
        <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">Reset Password</button>
      </form>
    </div>
  </div>
</section>
@endsection

</body>

</html>
