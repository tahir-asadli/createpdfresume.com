<div class="hero">
    <div>
        <div class="eyebrow-tag"><span
                class="dot"></span>{{ __(':count free templates · no credit card', ['count' => freePlanTemplateCount()]) }}
        </div>
        <h1>{!! __('Build a resume that <span>gets the callback.</span>') !!}</h1>
        <p class="sub">
            {{ __('Pick a template, drop in your experience, and export an ATS-friendly PDF in minutes — no formatting fights in Word.') }}
        </p>
        <div class="hero-actions">
            <a href="#" class="btn btn-accent btn-lg">{{ __('Create my resume') }}</a>
            <a href="#" class="link-secondary">{{ __('Import an existing resume →') }}</a>
        </div>
        <div class="metarow">
            <div class="metaitem">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" fill="none">
                    <path d="M5 13l4 4L19 7" />
                </svg>
                {!! __('<b>Drag & Drop</b> editor') !!}
            </div>
            <div class="metaitem">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" fill="none">
                    <path d="M5 13l4 4L19 7" />
                </svg>
                {!! __('One‑click <b>PDF export</b>') !!}
            </div>
            <div class="metaitem">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" fill="none">
                    <path d="M5 13l4 4L19 7" />
                </svg>
                {!! __('<b>ATS‑checked</b> formatting') !!}
            </div>
        </div>
    </div>
    <div class="hero-visual">
        <div class="doc-card">
            <div class="stamp"><span>ATS<br>READY</span></div>
            <div class="doc-head">
                <div class="doc-avatar">AC</div>
                <div>
                    <div class="doc-name">Aiden Cole</div>
                    <div class="doc-role">Senior product designer</div>
                </div>
            </div>
            <div class="doc-section-label">Experience</div>
            <div class="doc-line mid"></div>
            <div class="doc-line short"></div>
            <div class="doc-line mid" style="margin-top:10px;"></div>
            <div class="doc-line short"></div>
            <div class="doc-section-label">Skills</div>
            <div class="doc-tags">
                <div class="doc-tag">Figma</div>
                <div class="doc-tag">Design systems</div>
                <div class="doc-tag">User research</div>
            </div>
        </div>
    </div>
</div>
