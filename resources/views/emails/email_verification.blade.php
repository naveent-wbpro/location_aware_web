<p>
    Thank you for registering to LocationAware.io. Please click the link below to verify your email.
</p>

<p>
    <a href="{{ URL::to('/') }}/email_verification/update?token={{ $user->email_verification_token }}">
        Click Here to Verify Email
    </a>
</p>
