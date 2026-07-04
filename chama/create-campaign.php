<?php
// ============================================================
// ChamaFunds – create-campaign.php
// ============================================================
if (session_status() === PHP_SESSION_NONE) session_start();

// If not logged in, redirect to signup with hint
$isLoggedIn = isset($_SESSION['user_id']);
$userRole   = $_SESSION['role'] ?? 'guest';

$pageTitle       = 'Create Campaign – Start Fundraising in Uganda | ChamaFunds';
$pageDescription = 'Start your crowdfunding campaign in Uganda in under 60 seconds. Free to create, receive funds via MTN Mobile Money & Airtel Money.';

include __DIR__ . '/includes/header.php';
?>

<!-- ── Accordion + Preflight styles ─────────────────────────── -->
<style>
  .pf-accordion { display:flex; flex-direction:column; gap:0; margin-bottom:28px; border-radius:14px; overflow:hidden; border:1px solid #e5e7eb; }
  .pf-acc-item { border-bottom:1px solid #e5e7eb; }
  .pf-acc-item:last-child { border-bottom:none; }
  .pf-acc-trigger {
    width:100%; display:flex; align-items:center; gap:14px;
    padding:16px 20px; background:#fff; cursor:pointer;
    text-align:left; transition:background .18s;
    border:none; font-family:inherit;
  }
  .pf-acc-trigger:hover { background:#f9fafb; }
  .pf-acc-item.open .pf-acc-trigger { background:#f9fafb; }
  .pf-acc-icon-wrap {
    width:40px; height:40px; border-radius:11px; flex-shrink:0;
    background:rgba(26,42,108,.07);
    display:flex; align-items:center; justify-content:center;
    font-size:1rem; color:#1A2A6C; transition:background .18s;
  }
  .pf-acc-item.open .pf-acc-icon-wrap { background:#1A2A6C; color:#fff; }
  .pf-acc-label { flex:1; }
  .pf-acc-label strong { display:block; font-size:.92rem; font-weight:700; color:#1A2A6C; margin-bottom:2px; }
  .pf-acc-label span   { font-size:.78rem; color:#9ca3af; }
  .pf-acc-chevron { font-size:.75rem; color:#9ca3af; transition:transform .28s ease; flex-shrink:0; }
  .pf-acc-item.open .pf-acc-chevron { transform:rotate(180deg); color:#1A2A6C; }
  .pf-acc-body {
    max-height:0; overflow:hidden;
    transition:max-height .32s ease, padding .32s ease;
    background:#fff;
  }
  .pf-acc-item.open .pf-acc-body { max-height:400px; }
  .pf-acc-body-inner { padding:0 20px 18px 74px; }
  .pf-acc-body-inner p  { font-size:.88rem; color:#6b7280; line-height:1.7; margin-bottom:10px; }
  .pf-acc-body-inner ul { padding-left:16px; display:flex; flex-direction:column; gap:6px; }
  .pf-acc-body-inner ul li { font-size:.85rem; color:#4b5563; line-height:1.5; }
  .pf-req-tag {
    display:inline-flex; align-items:center; gap:5px;
    background:#f0fdf4; color:#065f46; border:1px solid #bbf7d0;
    border-radius:99px; padding:3px 10px; font-size:.72rem; font-weight:600;
    margin-right:6px; margin-top:4px;
  }
  .pf-req-tag.warn { background:#fff3cd; color:#856404; border-color:#ffe69c; }
  @media (max-width: 767px) {
    div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
  }

  /* ── Multi-image upload ─────────────────────────────────── */
  .mi-dropzone {
    border: 2px dashed #d1d5db; border-radius: 14px;
    padding: 28px 20px; text-align: center; cursor: pointer;
    transition: border-color .2s, background .2s; background: #fafafa;
    position: relative;
  }
  .mi-dropzone:hover, .mi-dropzone.drag-over {
    border-color: #FF6B4A; background: #fff8f6;
  }
  .mi-dropzone.has-files { padding: 14px 20px; }
  .mi-preview-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 10px; margin-top: 12px;
  }
  .mi-thumb {
    position: relative; border-radius: 12px; overflow: hidden;
    aspect-ratio: 4/3; background: #f3f4f6; border: 2px solid transparent;
    cursor: grab; transition: border-color .2s, box-shadow .2s;
  }
  .mi-thumb:first-child { border-color: #FF6B4A; }
  .mi-thumb:first-child::before {
    content: 'COVER'; position: absolute; top: 6px; left: 6px;
    background: #FF6B4A; color: #fff; font-size: .6rem; font-weight: 800;
    padding: 2px 7px; border-radius: 99px; z-index: 2; letter-spacing: .05em;
  }
  .mi-thumb img {
    width: 100%; height: 100%; object-fit: cover; display: block;
    pointer-events: none;
  }
  .mi-thumb-remove {
    position: absolute; top: 5px; right: 5px; z-index: 3;
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(0,0,0,.55); color: #fff; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: .65rem; transition: background .15s;
  }
  .mi-thumb-remove:hover { background: #ef4444; }
  .mi-add-btn {
    aspect-ratio: 4/3; border-radius: 12px; border: 2px dashed #d1d5db;
    background: #f9fafb; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    font-size: .75rem; color: #9ca3af; cursor: pointer; transition: all .2s;
    font-weight: 600; gap: 4px;
  }
  .mi-add-btn:hover { border-color: #FF6B4A; color: #FF6B4A; background: #fff8f6; }
  .mi-add-btn i { font-size: 1.2rem; }
  .mi-hint {
    font-size: .78rem; color: #6b7280; margin-top: 8px;
    display: flex; align-items: center; gap: 6px;
  }
</style>

<!-- PRE-FLIGHT GATE -->
<div id="preflightScreen" style="background:#f9fafb;min-height:100vh;padding:100px 0 64px;">
  <div class="container" style="max-width:1060px;">

    <!-- Header -->
    <div style="text-align:center;margin-bottom:36px;">
      <div style="width:64px;height:64px;background:rgba(26,42,108,.08);border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin:0 auto 16px;">📋</div>
      <h1 style="font-weight:800;color:#1A2A6C;font-size:1.5rem;margin-bottom:8px;">Before You Start</h1>
      <p style="color:#6b7280;font-size:.92rem;max-width:520px;margin:0 auto;line-height:1.7;">
        ChamaFunds verifies every campaign. Your campaign will be reviewed within
        <strong style="color:#1A2A6C;">48 working hours</strong> of submission.
      </p>
    </div>

    <!-- ── Two cards side-by-side ──────────────────────── -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;align-items:start;">

    <!-- ═══ CARD 1 — Requirements checklist (accordion) ══ -->
    <div style="background:#fff;border-radius:20px;box-shadow:0 4px 24px rgba(26,42,108,.09);overflow:hidden;">
      <div style="padding:24px 24px 16px;">
        <p style="font-weight:800;color:#1A2A6C;font-size:1rem;margin-bottom:2px;">
          <i class="fas fa-clipboard-list" style="color:#FF6B4A;margin-right:8px;"></i>Requirements Checklist
        </p>
        <p style="font-size:.8rem;color:#9ca3af;">Expand each item to see what you need to prepare</p>
      </div>
      <div class="pf-accordion" id="pfAccordion" style="margin-bottom:0;border-radius:0;border:none;border-top:1px solid #f3f4f6;">

      <!-- 1. Medical / Evidence -->
      <div class="pf-acc-item open">
        <button class="pf-acc-trigger" type="button" onclick="toggleAcc(this)">
          <div class="pf-acc-icon-wrap"><i class="fas fa-file-medical"></i></div>
          <div class="pf-acc-label">
            <strong>Medical / Evidence Document</strong>
            <span>Required for medical &amp; emergency campaigns</span>
          </div>
          <i class="fas fa-chevron-down pf-acc-chevron"></i>
        </button>
        <div class="pf-acc-body">
          <div class="pf-acc-body-inner">
            <p>Upload at least one document proving the need for funding.</p>
            <ul>
              <li>Doctor's note or official diagnosis letter</li>
              <li>Hospital admission or treatment plan</li>
              <li>Laboratory or radiology reports</li>
              <li>Specialist referral letter</li>
            </ul>
            <div style="margin-top:12px;">
              <span class="pf-req-tag"><i class="fas fa-check"></i> PDF or image</span>
              <span class="pf-req-tag"><i class="fas fa-check"></i> Max 5 MB</span>
              <span class="pf-req-tag warn"><i class="fas fa-clock"></i> Required before review</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. Supporting Document -->
      <div class="pf-acc-item">
        <button class="pf-acc-trigger" type="button" onclick="toggleAcc(this)">
          <div class="pf-acc-icon-wrap"><i class="fas fa-file-alt"></i></div>
          <div class="pf-acc-label">
            <strong>Supporting Document</strong>
            <span>Invoices, letters, or project plans</span>
          </div>
          <i class="fas fa-chevron-down pf-acc-chevron"></i>
        </button>
        <div class="pf-acc-body">
          <div class="pf-acc-body-inner">
            <p>A document supporting the specific reason for your campaign builds donor trust.</p>
            <ul>
              <li>School or university admission letter</li>
              <li>Itemised invoice or quotation</li>
              <li>Community project plan or proposal</li>
              <li>Burial or funeral cost estimate</li>
            </ul>
            <div style="margin-top:12px;">
              <span class="pf-req-tag"><i class="fas fa-check"></i> Any official document</span>
              <span class="pf-req-tag warn"><i class="fas fa-clock"></i> Speeds up approval</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 3. Valid ID -->
      <div class="pf-acc-item">
        <button class="pf-acc-trigger" type="button" onclick="toggleAcc(this)">
          <div class="pf-acc-icon-wrap"><i class="fas fa-id-card"></i></div>
          <div class="pf-acc-label">
            <strong>Valid Government-Issued ID</strong>
            <span>Mandatory identity verification for all creators</span>
          </div>
          <i class="fas fa-chevron-down pf-acc-chevron"></i>
        </button>
        <div class="pf-acc-body">
          <div class="pf-acc-body-inner">
            <p>Your ID is used only for identity checks and is never shared publicly.</p>
            <ul>
              <li>National ID card (front &amp; back)</li>
              <li>Valid passport (photo page)</li>
              <li>Driver's licence</li>
              <li>Refugee identification card</li>
            </ul>
            <div style="margin-top:12px;">
              <span class="pf-req-tag"><i class="fas fa-check"></i> Clearly legible</span>
              <span class="pf-req-tag"><i class="fas fa-check"></i> Not expired</span>
              <span class="pf-req-tag warn"><i class="fas fa-lock"></i> Kept confidential</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 4. Active Mobile Money -->
      <div class="pf-acc-item">
        <button class="pf-acc-trigger" type="button" onclick="toggleAcc(this)">
          <div class="pf-acc-icon-wrap"><i class="fas fa-mobile-alt"></i></div>
          <div class="pf-acc-label">
            <strong>Active Mobile Money Number</strong>
            <span>Where your contributions will be sent</span>
          </div>
          <i class="fas fa-chevron-down pf-acc-chevron"></i>
        </button>
        <div class="pf-acc-body">
          <div class="pf-acc-body-inner">
            <p>Funds are paid out directly to a registered mobile money account in your name.</p>
            <ul>
              <li>MTN Mobile Money (Uganda, Kenya, Rwanda…)</li>
              <li>Airtel Money</li>
              <li>Orange Money / Tigo Pesa</li>
              <li>M-Pesa (Kenya &amp; Tanzania)</li>
            </ul>
            <div style="margin-top:12px;">
              <span class="pf-req-tag"><i class="fas fa-check"></i> Must be active</span>
              <span class="pf-req-tag"><i class="fas fa-check"></i> In your name</span>
              <span class="pf-req-tag warn"><i class="fas fa-bolt"></i> Same-day payout</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 5. Accurate Campaign Info -->
      <div class="pf-acc-item">
        <button class="pf-acc-trigger" type="button" onclick="toggleAcc(this)">
          <div class="pf-acc-icon-wrap"><i class="fas fa-check-double"></i></div>
          <div class="pf-acc-label">
            <strong>Accurate Campaign Information</strong>
            <span>All details must be truthful and verifiable</span>
          </div>
          <i class="fas fa-chevron-down pf-acc-chevron"></i>
        </button>
        <div class="pf-acc-body">
          <div class="pf-acc-body-inner">
            <p>Providing false or misleading information is grounds for immediate removal.</p>
            <ul>
              <li>Real names and contact details only</li>
              <li>Genuine photos relevant to your cause</li>
              <li>Honest and specific campaign story</li>
              <li>Realistic fundraising goal</li>
            </ul>
            <div style="margin-top:12px;">
              <span class="pf-req-tag warn"><i class="fas fa-exclamation-triangle"></i> Misuse = account ban</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 6. Campaign Photo -->
      <div class="pf-acc-item">
        <button class="pf-acc-trigger" type="button" onclick="toggleAcc(this)">
          <div class="pf-acc-icon-wrap"><i class="fas fa-camera"></i></div>
          <div class="pf-acc-label">
            <strong>Campaign Cover Photo</strong>
            <span>A clear, relevant image increases donations by up to 3×</span>
          </div>
          <i class="fas fa-chevron-down pf-acc-chevron"></i>
        </button>
        <div class="pf-acc-body">
          <div class="pf-acc-body-inner">
            <p>A strong cover photo makes your campaign stand out and builds immediate trust.</p>
            <ul>
              <li>Clear, well-lit photo of the beneficiary or cause</li>
              <li>No logos, text overlays, or watermarks</li>
              <li>JPG or PNG, minimum 800 × 600 px</li>
              <li>Max file size: 5 MB</li>
            </ul>
            <div style="margin-top:12px;">
              <span class="pf-req-tag"><i class="fas fa-check"></i> JPG / PNG</span>
              <span class="pf-req-tag"><i class="fas fa-check"></i> Max 5 MB</span>
              <span class="pf-req-tag warn"><i class="fas fa-star"></i> Mandatory</span>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.pf-accordion -->
    </div><!-- /.card 1 -->

    <!-- ═══ CARD 2 — Platform Fee Calculator ════════════ -->
    <div style="background:#fff;border-radius:20px;box-shadow:0 4px 24px rgba(26,42,108,.09);overflow:hidden;display:flex;flex-direction:column;">
      <div style="padding:24px 24px 16px;">
        <p style="font-weight:800;color:#1A2A6C;font-size:1rem;margin-bottom:2px;">
          <i class="fas fa-percent" style="color:#FF6B4A;margin-right:8px;"></i>Platform Fee Calculator
        </p>
        <p style="font-size:.8rem;color:#9ca3af;">See exactly what you'll receive from every contribution</p>
      </div>

      <!-- Slider -->
      <div style="padding:0 24px 20px;border-bottom:1px solid #f3f4f6;">
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:12px;">
          <label style="font-size:.8rem;color:#6b7280;min-width:100px;">Goal (UGX)</label>
          <input type="range" id="feeSlider" min="10000" max="2000000" step="10000" value="500000" style="flex:1;min-width:120px;" />
          <span style="font-size:.9rem;font-weight:700;color:#1A2A6C;min-width:110px;text-align:right;" id="feeDisplay">UGX 500,000</span>
        </div>
        <div class="fee-calc-result">
          <div class="fee-calc-box"><p class="label">You receive</p><p class="value" id="netDisplay">UGX 462,500</p></div>
          <div class="fee-calc-box"><p class="label">Platform fee (7.5%)</p><p class="value coral" id="feeAmount">UGX 37,500</p></div>
        </div>
      </div>

      <!-- 6 fee info rows -->
      <div style="padding:16px 24px;display:flex;flex-direction:column;gap:0;flex:1;">

        <div style="display:flex;align-items:flex-start;gap:12px;padding:13px 0;border-bottom:1px solid #f9fafb;">
          <div style="width:34px;height:34px;border-radius:9px;background:rgba(255,107,74,.09);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-tag" style="font-size:.8rem;color:#FF6B4A;"></i>
          </div>
          <div>
            <p style="font-size:.85rem;font-weight:700;color:#1A2A6C;margin-bottom:2px;">7.5% Transaction Fee</p>
            <p style="font-size:.78rem;color:#9ca3af;line-height:1.5;">Deducted only at withdrawal, never upfront.</p>
          </div>
        </div>

        <div style="display:flex;align-items:flex-start;gap:12px;padding:13px 0;border-bottom:1px solid #f9fafb;">
          <div style="width:34px;height:34px;border-radius:9px;background:rgba(16,185,129,.09);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-rocket" style="font-size:.8rem;color:#10b981;"></i>
          </div>
          <div>
            <p style="font-size:.85rem;font-weight:700;color:#1A2A6C;margin-bottom:2px;">Free to Create</p>
            <p style="font-size:.78rem;color:#9ca3af;line-height:1.5;">No setup fee. Creating a campaign costs nothing.</p>
          </div>
        </div>

        <div style="display:flex;align-items:flex-start;gap:12px;padding:13px 0;border-bottom:1px solid #f9fafb;">
          <div style="width:34px;height:34px;border-radius:9px;background:rgba(26,42,108,.07);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-bolt" style="font-size:.8rem;color:#1A2A6C;"></i>
          </div>
          <div>
            <p style="font-size:.85rem;font-weight:700;color:#1A2A6C;margin-bottom:2px;">Same-Day Payout</p>
            <p style="font-size:.78rem;color:#9ca3af;line-height:1.5;">Withdraw funds to mobile money within hours of approval.</p>
          </div>
        </div>

        <div style="display:flex;align-items:flex-start;gap:12px;padding:13px 0;border-bottom:1px solid #f9fafb;">
          <div style="width:34px;height:34px;border-radius:9px;background:rgba(245,158,11,.09);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-shield-alt" style="font-size:.8rem;color:#F59E0B;"></i>
          </div>
          <div>
            <p style="font-size:.85rem;font-weight:700;color:#1A2A6C;margin-bottom:2px;">Secure &amp; Transparent</p>
            <p style="font-size:.78rem;color:#9ca3af;line-height:1.5;">Every contribution is logged on a live public ledger.</p>
          </div>
        </div>

        <div style="display:flex;align-items:flex-start;gap:12px;padding:13px 0;border-bottom:1px solid #f9fafb;">
          <div style="width:34px;height:34px;border-radius:9px;background:rgba(59,130,246,.09);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-mobile-alt" style="font-size:.8rem;color:#3b82f6;"></i>
          </div>
          <div>
            <p style="font-size:.85rem;font-weight:700;color:#1A2A6C;margin-bottom:2px;">MTN &amp; Airtel Money</p>
            <p style="font-size:.78rem;color:#9ca3af;line-height:1.5;">Works with all major mobile money networks in Africa.</p>
          </div>
        </div>

        <div style="display:flex;align-items:flex-start;gap:12px;padding:13px 0;">
          <div style="width:34px;height:34px;border-radius:9px;background:rgba(16,185,129,.09);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-chart-line" style="font-size:.8rem;color:#10b981;"></i>
          </div>
          <div>
            <p style="font-size:.85rem;font-weight:700;color:#1A2A6C;margin-bottom:2px;">Live Donation Tracking</p>
            <p style="font-size:.78rem;color:#9ca3af;line-height:1.5;">Watch contributions roll in with real-time updates.</p>
          </div>
        </div>

      </div><!-- /fee rows -->
    </div><!-- /.card 2 -->

    </div><!-- /.two-card grid -->

    <!-- Confirm checkbox -->
    <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:24px;padding:16px;background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(26,42,108,.07);">
      <input type="checkbox" id="preflightCheck" style="width:17px;height:17px;margin-top:2px;cursor:pointer;flex-shrink:0;accent-color:#FF6B4A;" />
      <label for="preflightCheck" style="font-size:.88rem;color:#6b7280;cursor:pointer;line-height:1.6;">
        I have read the requirements above and understand my campaign will be reviewed within
        <strong style="color:#1A2A6C;">48 working hours</strong>.
      </label>
    </div>

    <button id="preflightProceed" class="btn btn-primary btn-block btn-lg" disabled style="opacity:.5;cursor:not-allowed;">
      I'm Ready — Start My Campaign <i class="fas fa-arrow-right" style="margin-left:8px;"></i>
    </button>

  </div>
</div>

<!-- CAMPAIGN FORM (hidden until preflight) -->
<div id="campaignFormScreen" style="display:none;background:#f9fafb;padding:40px 0 64px;min-height:100vh;">
  <div class="container" style="max-width:960px;">
    <div style="display:grid;grid-template-columns:1fr 300px;gap:28px;align-items:start;">
      <div style="background:#fff;border-radius:20px;padding:36px;box-shadow:0 2px 16px rgba(26,42,108,.07);">
        <h1 style="font-weight:800;color:#1A2A6C;font-size:1.4rem;margin-bottom:4px;">Start a Campaign</h1>
        <p style="color:#9ca3af;font-size:.86rem;margin-bottom:24px;">Your campaign goes live within 48 hours after review.</p>

        <!-- Step Indicator -->
        <div class="step-indicator" style="margin-bottom:8px;">
          <div class="step-dot active" id="step1Dot">1</div>
          <div class="step-line" id="line1"></div>
          <div class="step-dot" id="step2Dot">2</div>
          <div class="step-line" id="line2"></div>
          <div class="step-dot" id="step3Dot">3</div>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.72rem;color:#9ca3af;margin-bottom:28px;">
          <span id="stepLabel1" style="font-weight:600;">Campaign Details</span>
          <span id="stepLabel2">Goal & Payment</span>
          <span id="stepLabel3">Evidence & Launch</span>
        </div>

        <div id="campaignFormMsg" style="display:none;padding:12px;border-radius:10px;font-size:.88rem;margin-bottom:16px;"></div>

        <form id="campaignForm" enctype="multipart/form-data">
          <!-- STEP 1 -->
          <div id="formStep1">
            <div class="form-group">
              <label class="form-label">Campaign Title <span class="required">*</span></label>
              <input type="text" id="campaignTitle" name="title" class="form-input" placeholder="e.g. Family Medical Fund" required />
            </div>
            <div class="form-group">
              <label class="form-label">Category <span class="required">*</span></label>
              <select id="campaignCategory" name="category" class="form-input" required>
                <option value="">Select a category</option>
                <option>Family</option><option>Education</option><option>Medical</option>
                <option>Community</option><option>Business</option><option>Emergency</option><option>Other</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Campaign Story <span class="required">*</span></label>
              <textarea id="campaignStory" name="description" class="form-input" rows="6" placeholder="Tell your story — why are you raising funds? Be specific and personal." required></textarea>
            </div>
            <div class="form-group">
              <label class="form-label">
                Campaign Photos <span class="required">*</span>
                <span style="font-size:.74rem;font-weight:400;color:#9ca3af;margin-left:6px;">Up to 6 images · First = cover</span>
              </label>

              <!-- Drop zone -->
              <div class="mi-dropzone" id="miDropzone">
                <input type="file" id="fileInput" name="images[]" accept="image/jpeg,image/png,image/webp"
                       multiple style="display:none;" />
                <div class="mi-dropzone-inner" id="miDropzoneInner">
                  <i class="fas fa-images" style="font-size:2.2rem;color:#d1d5db;margin-bottom:10px;display:block;"></i>
                  <p style="font-weight:700;color:#4b5563;font-size:.9rem;margin-bottom:4px;">
                    Drop photos here or <span style="color:#FF6B4A;cursor:pointer;" onclick="document.getElementById('fileInput').click()">browse</span>
                  </p>
                  <p style="font-size:.75rem;color:#9ca3af;">JPG, PNG, WEBP · Max 5 MB each · Up to 6 images</p>
                </div>
              </div>

              <!-- Preview grid -->
              <div class="mi-preview-grid" id="miPreviewGrid"></div>
              <p class="mi-hint" id="miHint" style="display:none;">
                <i class="fas fa-star" style="color:#F59E0B;"></i>
                First image is the <strong>cover photo</strong>. Drag to reorder.
              </p>
            </div>
            <div class="form-group">
              <label class="form-label">End Date (optional)</label>
              <input type="date" name="end_date" class="form-input" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" />
            </div>
            <div style="display:flex;justify-content:flex-end;margin-top:8px;">
              <button type="button" id="nextStep1" class="btn btn-primary">Next: Goal & Payment <i class="fas fa-arrow-right" style="margin-left:6px;"></i></button>
            </div>
          </div>

          <!-- STEP 2 -->
          <div id="formStep2" style="display:none;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="form-group">
              <div>
                <label class="form-label">Goal Amount <span class="required">*</span></label>
                <input type="number" id="goalAmount" name="goal_amount" class="form-input" placeholder="500000" min="1000" required />
              </div>
              <div>
                <label class="form-label">Currency</label>
                <select name="currency" class="form-input">
                  <option>UGX</option><option>KES</option><option>RWF</option><option>NGN</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Mobile Money Number <span class="required">*</span></label>
              <input type="tel" name="mobile_money_number" class="form-input" placeholder="e.g. 256712345678" required />
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="form-group">
              <div>
                <label class="form-label">Mobile Money Network <span class="required">*</span></label>
                <select name="mobile_money_network" class="form-input" required>
                  <option>MTN Mobile Money</option><option>Airtel Money</option><option>Orange Money</option>
                </select>
              </div>
              <div>
                <label class="form-label">Country <span class="required">*</span></label>
                <select name="country" class="form-input" required>
                  <option>Uganda</option><option>Kenya</option><option>Rwanda</option><option>Nigeria</option>
                </select>
              </div>
            </div>
            <div style="display:flex;gap:12px;justify-content:space-between;">
              <button type="button" id="prevStep2" class="btn btn-outline"><i class="fas fa-arrow-left" style="margin-right:6px;"></i>Back</button>
              <button type="button" id="nextStep2" class="btn btn-primary">Next: Evidence & Launch <i class="fas fa-arrow-right" style="margin-left:6px;"></i></button>
            </div>
          </div>

          <!-- STEP 3 -->
          <div id="formStep3" style="display:none;">
            <!-- Summary -->
            <div style="background:#f9fafb;border-radius:14px;padding:20px;margin-bottom:24px;">
              <p style="font-weight:700;color:#1A2A6C;margin-bottom:12px;font-size:.9rem;">📋 Campaign Summary</p>
              <div style="font-size:.86rem;color:#6b7280;display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;justify-content:space-between;"><span>Title:</span><strong style="color:#1A2A6C;" id="reviewTitle">—</strong></div>
                <div style="display:flex;justify-content:space-between;"><span>Category:</span><strong style="color:#1A2A6C;" id="reviewCategory">—</strong></div>
                <div style="display:flex;justify-content:space-between;"><span>Goal:</span><strong style="color:#1A2A6C;" id="reviewGoal">—</strong></div>
                <div style="display:flex;justify-content:space-between;"><span>Platform fee:</span><strong style="color:#FF6B4A;">7.5% per contribution</strong></div>
              </div>
            </div>

            <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:24px;padding:14px;background:#fff5f3;border-radius:12px;border:1px solid #ffe4dd;">
              <input type="checkbox" id="terms" style="margin-top:2px;width:16px;height:16px;cursor:pointer;flex-shrink:0;" />
              <label for="terms" style="font-size:.84rem;color:#6b7280;">I agree to the <a href="#" style="color:#FF6B4A;">Terms of Service</a> and confirm all information is accurate and genuine.</label>
            </div>

            <div style="display:flex;flex-wrap:wrap;gap:12px;">
              <button type="button" id="prevStep3" class="btn btn-outline"><i class="fas fa-arrow-left" style="margin-right:6px;"></i>Back</button>
              <button type="button" id="launchBtn" class="btn btn-primary btn-lg" style="flex:1;justify-content:center;">🚀 Submit Campaign for Review</button>
            </div>
          </div>
        </form>
      </div>

      <!-- Checklist Sidebar -->
      <div style="position:sticky;top:84px;display:flex;flex-direction:column;gap:16px;">
        <div style="background:#fff;border-radius:16px;padding:24px;box-shadow:0 2px 12px rgba(26,42,108,.07);">
          <p style="font-weight:800;color:#1A2A6C;font-size:.88rem;margin-bottom:16px;">Your Progress</p>
          <div style="display:flex;flex-direction:column;gap:10px;font-size:.84rem;" id="checklistItems">
            <div class="cl-item" id="cl-title" style="display:flex;align-items:center;gap:10px;"><i class="far fa-circle" style="color:#d1d5db;width:16px;"></i><span style="color:#9ca3af;">Campaign Title</span></div>
            <div class="cl-item" id="cl-category" style="display:flex;align-items:center;gap:10px;"><i class="far fa-circle" style="color:#d1d5db;width:16px;"></i><span style="color:#9ca3af;">Category</span></div>
            <div class="cl-item" id="cl-story" style="display:flex;align-items:center;gap:10px;"><i class="far fa-circle" style="color:#d1d5db;width:16px;"></i><span style="color:#9ca3af;">Campaign Story</span></div>
            <div class="cl-item" id="cl-goal" style="display:flex;align-items:center;gap:10px;"><i class="far fa-circle" style="color:#d1d5db;width:16px;"></i><span style="color:#9ca3af;">Goal Amount</span></div>
            <div class="cl-item" id="cl-momo" style="display:flex;align-items:center;gap:10px;"><i class="far fa-circle" style="color:#d1d5db;width:16px;"></i><span style="color:#9ca3af;">Mobile Money</span></div>
            <div class="cl-item" id="cl-photo" style="display:flex;align-items:center;gap:10px;"><i class="far fa-circle" style="color:#d1d5db;width:16px;"></i><span style="color:#9ca3af;">Campaign Photo <span style="color:#ef4444;">*</span></span></div>
            <div class="cl-item" id="cl-terms" style="display:flex;align-items:center;gap:10px;"><i class="far fa-circle" style="color:#d1d5db;width:16px;"></i><span style="color:#9ca3af;">Terms Accepted</span></div>
          </div>
          <hr class="divider" style="margin:16px 0;" />
          <p style="font-size:.75rem;color:#9ca3af;"><i class="fas fa-info-circle" style="margin-right:4px;"></i>Fields marked <span style="color:#ef4444;">*</span> are required.</p>
        </div>
        <div style="background:#f0fdf4;border-radius:16px;padding:20px;box-shadow:0 2px 12px rgba(16,185,129,.07);">
          <p style="font-size:.8rem;font-weight:700;color:#065f46;margin-bottom:8px;"><i class="fas fa-clock" style="margin-right:6px;"></i>Review Timeline</p>
          <p style="font-size:.78rem;color:#6b7280;line-height:1.6;">After submission your campaign is reviewed within <strong>48 working hours</strong>. You'll get an SMS confirmation.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script>
// ── Accordion toggle ────────────────────────────────────────
function toggleAcc(trigger) {
  var item = trigger.closest('.pf-acc-item');
  var isOpen = item.classList.contains('open');
  // close all
  document.querySelectorAll('.pf-acc-item').forEach(function(i){ i.classList.remove('open'); });
  // open clicked if it was closed
  if (!isOpen) item.classList.add('open');
}

// Preflight
var preflightCheck   = document.getElementById('preflightCheck');
var preflightProceed = document.getElementById('preflightProceed');
var preflightScreen  = document.getElementById('preflightScreen');
var campaignFormScreen = document.getElementById('campaignFormScreen');

preflightCheck.addEventListener('change', function() {
  preflightProceed.disabled = !this.checked;
  preflightProceed.style.opacity = this.checked ? '1' : '.5';
  preflightProceed.style.cursor = this.checked ? 'pointer' : 'not-allowed';
});
preflightProceed.addEventListener('click', function() {
  preflightScreen.style.display = 'none';
  campaignFormScreen.style.display = 'block';
  window.scrollTo(0, 0);
});

// Steps
var steps = ['formStep1','formStep2','formStep3'];
var cur = 0;
function goTo(n) {
  document.getElementById(steps[cur]).style.display='none';
  cur = n;
  document.getElementById(steps[cur]).style.display='block';
  ['step1Dot','step2Dot','step3Dot'].forEach(function(id,i){
    var dot = document.getElementById(id);
    dot.classList.remove('active','done');
    if (i < cur) dot.classList.add('done');
    else if (i === cur) dot.classList.add('active');
  });
  ['line1','line2'].forEach(function(id,i){
    var ln = document.getElementById(id);
    ln.classList.toggle('done', i < cur);
  });
  ['stepLabel1','stepLabel2','stepLabel3'].forEach(function(id,i){
    document.getElementById(id).style.fontWeight = i===cur?'700':'400';
    document.getElementById(id).style.color = i===cur?'#1A2A6C':'#9ca3af';
  });
  window.scrollTo(0,0);
}

document.getElementById('nextStep1').addEventListener('click', function() {
  if (!document.getElementById('campaignTitle').value.trim()) return window.showToast('Enter a campaign title','error');
  if (!document.getElementById('campaignCategory').value) return window.showToast('Select a category','error');
  if (!document.getElementById('campaignStory').value.trim()) return window.showToast('Write a campaign story','error');
  if (!miFiles.length) return window.showToast('📷 At least one campaign photo is required.','error');
  updateChecklist();
  goTo(1);
});
document.getElementById('prevStep2').addEventListener('click', function(){ goTo(0); });
document.getElementById('nextStep2').addEventListener('click', function() {
  if (!document.getElementById('goalAmount').value || Number(document.getElementById('goalAmount').value) < 1000) return window.showToast('Enter a valid goal amount (min 1,000)','error');
  document.getElementById('reviewTitle').textContent    = document.getElementById('campaignTitle').value;
  document.getElementById('reviewCategory').textContent = document.getElementById('campaignCategory').value;
  document.getElementById('reviewGoal').textContent     = Number(document.getElementById('goalAmount').value).toLocaleString();
  updateChecklist();
  goTo(2);
});
document.getElementById('prevStep3').addEventListener('click', function(){ goTo(1); });

document.getElementById('launchBtn').addEventListener('click', async function() {
  if (!document.getElementById('terms').checked) return window.showToast('Please accept the terms','error');
  var btn = this;
  btn.disabled = true;
  btn.textContent = '⏳ Submitting…';

  var fd = new FormData(document.getElementById('campaignForm'));
  fd.append('action','create');

  <?php if (!$isLoggedIn): ?>
  // Redirect to signup if not logged in
  window.showToast('Please sign up or log in to create a campaign.','error');
  setTimeout(function(){ window.location.href='<?= BASE ?>/signup.php'; }, 1500);
  btn.disabled=false; btn.textContent='🚀 Submit Campaign for Review';
  return;
  <?php endif; ?>

  try {
    var res  = await fetch('<?= BASE ?>/api/campaigns.php?action=create',{method:'POST',body:fd});
    var data = await res.json();
    if (data.success) {
      var msgEl = document.getElementById('campaignFormMsg');
      msgEl.style.cssText='display:block;background:#d1fae5;color:#065f46;padding:12px;border-radius:10px;font-size:.88rem;margin-bottom:16px;';
      msgEl.textContent = data.message;
      window.scrollTo(0,0);
      setTimeout(function(){ window.location.href='<?= BASE ?>/dashboard.php'; }, 2500);
    } else {
      window.showToast(data.message,'error');
      btn.disabled=false; btn.textContent='🚀 Submit Campaign for Review';
    }
  } catch(e) {
    window.showToast('An error occurred. Please try again.','error');
    btn.disabled=false; btn.textContent='🚀 Submit Campaign for Review';
  }
});

// Checklist
function checkItem(id, ok) {
  var el = document.getElementById(id);
  if (!el) return;
  var icon = el.querySelector('i');
  var span = el.querySelector('span');
  if (ok) { icon.className='fas fa-check-circle'; icon.style.color='#10b981'; span.style.color='#1f2937'; }
  else     { icon.className='far fa-circle'; icon.style.color='#d1d5db'; span.style.color='#9ca3af'; }
}
function updateChecklist() {
  checkItem('cl-title',    !!document.getElementById('campaignTitle')?.value.trim());
  checkItem('cl-category', !!document.getElementById('campaignCategory')?.value);
  checkItem('cl-story',    !!document.getElementById('campaignStory')?.value.trim());
  checkItem('cl-goal',     !!document.getElementById('goalAmount')?.value);
  checkItem('cl-photo',    miFiles.length > 0);
  checkItem('cl-terms',    document.getElementById('terms')?.checked);
}
document.getElementById('campaignTitle')?.addEventListener('input',  updateChecklist);
document.getElementById('campaignCategory')?.addEventListener('change', updateChecklist);
document.getElementById('campaignStory')?.addEventListener('input',  updateChecklist);
document.getElementById('goalAmount')?.addEventListener('input',     updateChecklist);
document.getElementById('fileInput')?.addEventListener('change',     updateChecklist);
document.getElementById('terms')?.addEventListener('change',         updateChecklist);

// ── Multi-image upload logic ────────────────────────────────
var miFiles    = [];   // array of File objects (ordered)
var MAX_IMAGES = 6;

var dropzone   = document.getElementById('miDropzone');
var fileInput  = document.getElementById('fileInput');
var previewGrid= document.getElementById('miPreviewGrid');
var miHint     = document.getElementById('miHint');

// Click anywhere on dropzone → open file picker
dropzone.addEventListener('click', function(e) {
  if (!e.target.closest('.mi-thumb')) fileInput.click();
});

// Drag & drop
dropzone.addEventListener('dragover', function(e) {
  e.preventDefault(); dropzone.classList.add('drag-over');
});
dropzone.addEventListener('dragleave', function() {
  dropzone.classList.remove('drag-over');
});
dropzone.addEventListener('drop', function(e) {
  e.preventDefault(); dropzone.classList.remove('drag-over');
  addFiles(Array.from(e.dataTransfer.files));
});

// File input change
fileInput.addEventListener('change', function() {
  addFiles(Array.from(this.files));
  this.value = ''; // reset so same file can be re-added after remove
});

function addFiles(newFiles) {
  newFiles.forEach(function(f) {
    if (miFiles.length >= MAX_IMAGES) {
      window.showToast('Maximum 6 images allowed', 'error'); return;
    }
    var allowed = ['image/jpeg','image/png','image/webp'];
    if (!allowed.includes(f.type)) {
      window.showToast(f.name + ': only JPG/PNG/WEBP allowed', 'error'); return;
    }
    if (f.size > 5 * 1024 * 1024) {
      window.showToast(f.name + ': exceeds 5 MB', 'error'); return;
    }
    miFiles.push(f);
  });
  renderPreviews();
  updateChecklist();
}

function renderPreviews() {
  previewGrid.innerHTML = '';

  miFiles.forEach(function(file, idx) {
    var thumb = document.createElement('div');
    thumb.className = 'mi-thumb';
    thumb.draggable = true;
    thumb.dataset.idx = idx;

    var img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.alt = file.name;

    var removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'mi-thumb-remove';
    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
    removeBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      miFiles.splice(idx, 1);
      renderPreviews();
      updateChecklist();
    });

    thumb.appendChild(img);
    thumb.appendChild(removeBtn);
    previewGrid.appendChild(thumb);

    // Drag-to-reorder
    thumb.addEventListener('dragstart', function(e) {
      e.dataTransfer.setData('text/plain', idx);
      thumb.style.opacity = '.45';
    });
    thumb.addEventListener('dragend', function() { thumb.style.opacity = '1'; });
    thumb.addEventListener('dragover', function(e) {
      e.preventDefault(); thumb.style.outline = '2px solid #FF6B4A';
    });
    thumb.addEventListener('dragleave', function() { thumb.style.outline = ''; });
    thumb.addEventListener('drop', function(e) {
      e.preventDefault(); thumb.style.outline = '';
      var fromIdx = parseInt(e.dataTransfer.getData('text/plain'));
      var toIdx   = parseInt(thumb.dataset.idx);
      if (fromIdx === toIdx) return;
      var moved = miFiles.splice(fromIdx, 1)[0];
      miFiles.splice(toIdx, 0, moved);
      renderPreviews();
    });
  });

  // Add more button (if under limit)
  if (miFiles.length < MAX_IMAGES) {
    var addBtn = document.createElement('div');
    addBtn.className = 'mi-add-btn';
    addBtn.innerHTML = '<i class="fas fa-plus"></i><span>Add photo</span>';
    addBtn.addEventListener('click', function() { fileInput.click(); });
    previewGrid.appendChild(addBtn);
  }

  // Show/hide hint and dropzone inner
  var inner = document.getElementById('miDropzoneInner');
  if (miFiles.length > 0) {
    dropzone.classList.add('has-files');
    inner.style.display = 'none';
    miHint.style.display = 'flex';
  } else {
    dropzone.classList.remove('has-files');
    inner.style.display = 'block';
    miHint.style.display = 'none';
  }
}

// Override form submission to append all images as images[]
document.getElementById('launchBtn').removeEventListener && null;
document.getElementById('campaignForm').addEventListener('submit', function() {}, false);
// Patch the launchBtn handler — append miFiles to FormData
var _origLaunch = document.getElementById('launchBtn').onclick;
document.getElementById('launchBtn').addEventListener('click', function patchFiles() {
  // Already handled below via the existing listener,
  // but we need to ensure FormData includes miFiles.
  // Inject a hidden DataTransfer so the form picks them up.
  var dt = new DataTransfer();
  miFiles.forEach(function(f) { dt.items.add(f); });
  fileInput.files = dt.files;
}, true); // capture phase — fires before the existing listener

</script>
