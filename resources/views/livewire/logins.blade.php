<div class="social-login-container">
    <div class="social-text">{{ __('Sign in and register with social media accounts') }}</div>
    <div class="flex flex-col">
        <a href="{{ route('google') }}" class="social-btn google-btn">
            <x-icons.google />
            <span>{{ __('Sign in with Google account') }}</span>
        </a>
        <a href="{{ route('github') }}" class="social-btn github-btn">
            <x-icons.github />
            <span>{{ __('Sign in with Github account') }}</span>
        </a>
        <a href="{{ route('linkedin') }}" class="social-btn linkedin-btn">
            <x-icons.linkedin />
            <span>{{ __('Sign in with LinkedIn account') }}</span>
        </a>
    </div>
    <style>
        .social-login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0 0 20px 0;
            gap: 15px;
        }

        .social-text {
            font-size: 15px;
            color: #333;
        }

        .social-btn {
            display: flex;
            gap: 8px;
            align-items: center;
            color: white;
            border-radius: 10px;
            padding: 8px 24px;
            margin: 5px 0;
        }

        .google-btn {
            background: #ea4335;
        }

        .github-btn {
            background: #000000;
        }

        .linkedin-btn {
            background: #0077B5;
        }
    </style>
</div>
