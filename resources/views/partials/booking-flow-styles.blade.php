<style>
  .booking-shell {
    position: relative;
  }

  .booking-shell::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
      radial-gradient(circle at top left, rgba(130, 198, 141, 0.14), transparent 30%),
      radial-gradient(circle at top right, rgba(242, 201, 76, 0.12), transparent 28%),
      linear-gradient(180deg, #f9fcf7 0%, #ffffff 55%);
    pointer-events: none;
  }

  .booking-shell > .container {
    position: relative;
    z-index: 1;
  }

  .booking-progress {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 32px;
  }

  .booking-progress__item {
    position: relative;
    display: flex;
    gap: 14px;
    align-items: flex-start;
    padding: 18px 20px;
    border-radius: 22px;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
  }

  .booking-progress__item.is-active {
    border-color: rgba(130, 198, 141, 0.72);
    box-shadow: 0 24px 55px rgba(130, 198, 141, 0.18);
    transform: translateY(-2px);
  }

  .booking-progress__item.is-complete {
    border-color: rgba(47, 109, 57, 0.18);
    background: linear-gradient(180deg, #ffffff 0%, #f7fcf5 100%);
  }

  .booking-progress__badge {
    width: 46px;
    height: 46px;
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    color: #1f2937;
    background: #f3f4f6;
    flex-shrink: 0;
  }

  .booking-progress__item.is-active .booking-progress__badge,
  .booking-progress__item.is-complete .booking-progress__badge {
    background: linear-gradient(135deg, #82c68d 0%, #5aa66a 100%);
    color: #ffffff;
  }

  .booking-progress__eyebrow {
    display: block;
    margin-bottom: 4px;
    font-size: 0.75rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #6b7280;
  }

  .booking-progress__title {
    display: block;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
  }

  .booking-progress__desc {
    margin: 0;
    font-size: 0.92rem;
    line-height: 1.6;
    color: #6b7280;
  }

  .booking-panel,
  .booking-summary,
  .booking-service-card {
    border-radius: 26px;
    border: 1px solid rgba(15, 23, 42, 0.08);
    background: #ffffff;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
  }

  .booking-panel {
    padding: 32px;
  }

  .booking-summary {
    padding: 28px;
    position: sticky;
    top: 110px;
  }

  .booking-kicker {
    display: inline-flex;
    padding: 7px 14px;
    border-radius: 999px;
    background: rgba(130, 198, 141, 0.16);
    color: #2f6d39;
    font-weight: 600;
    font-size: 0.82rem;
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }

  .booking-lead {
    margin: 14px 0 0;
    color: #6b7280;
    line-height: 1.8;
  }

  .booking-service-card {
    overflow: hidden;
    height: 100%;
  }

  .booking-service-card__image {
    position: relative;
    height: 220px;
    overflow: hidden;
  }

  .booking-service-card__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .booking-service-card__body {
    padding: 24px;
  }

  .booking-service-card__meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 14px;
  }

  .booking-price-tag,
  .booking-location-tag {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 7px 12px;
    font-size: 0.82rem;
    line-height: 1;
  }

  .booking-price-tag {
    background: #0f172a;
    color: #ffffff;
    font-weight: 600;
  }

  .booking-location-tag {
    background: #f3f4f6;
    color: #475569;
  }

  .booking-title {
    margin-bottom: 8px;
    font-size: 1.2rem;
    font-weight: 700;
    color: #111827;
  }

  .booking-copy {
    margin-bottom: 20px;
    color: #6b7280;
    line-height: 1.75;
  }

  .booking-check {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    padding: 14px 16px;
    border-radius: 18px;
    background: #f8fafc;
    border: 1px solid rgba(148, 163, 184, 0.18);
    cursor: pointer;
    margin-bottom: 18px;
  }

  .booking-check input {
    margin-top: 4px;
  }

  .booking-quantity label,
  .booking-field label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #334155;
  }

  .booking-quantity input,
  .booking-field input,
  .booking-field textarea,
  .booking-field select {
    border-radius: 16px;
    border: 1px solid rgba(148, 163, 184, 0.28);
    min-height: 52px;
    box-shadow: none;
  }

  .booking-field textarea {
    min-height: 120px;
    padding-top: 14px;
  }

  .booking-choice-grid {
    display: grid;
    gap: 14px;
  }

  .booking-choice {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    padding: 18px 18px;
    border-radius: 20px;
    border: 1px solid rgba(148, 163, 184, 0.18);
    background: #ffffff;
    cursor: pointer;
  }

  .booking-choice.is-selected {
    border-color: rgba(130, 198, 141, 0.72);
    box-shadow: 0 18px 45px rgba(130, 198, 141, 0.14);
    background: #fbfef9;
  }

  .booking-choice__title {
    display: block;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
  }

  .booking-choice__desc {
    margin: 0;
    color: #6b7280;
    line-height: 1.6;
    font-size: 0.92rem;
  }

  .booking-pill {
    display: inline-flex;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(15, 23, 42, 0.06);
    color: #0f172a;
    font-size: 0.82rem;
    font-weight: 600;
  }

  .booking-summary__section + .booking-summary__section {
    margin-top: 22px;
    padding-top: 22px;
    border-top: 1px solid rgba(148, 163, 184, 0.18);
  }

  .booking-summary__label {
    margin-bottom: 10px;
    font-size: 0.78rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #94a3b8;
    font-weight: 700;
  }

  .booking-summary__item {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 12px;
    color: #475569;
  }

  .booking-summary__item strong {
    color: #111827;
  }

  .booking-summary__empty {
    margin: 0;
    color: #94a3b8;
    line-height: 1.7;
  }

  .booking-summary__total {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    margin-top: 18px;
    padding-top: 18px;
    border-top: 1px dashed rgba(148, 163, 184, 0.3);
    font-size: 1.02rem;
    font-weight: 700;
    color: #0f172a;
  }

  .booking-inline-note {
    margin-top: 18px;
    padding: 16px 18px;
    border-radius: 18px;
    background: rgba(130, 198, 141, 0.12);
    color: #2f6d39;
    line-height: 1.75;
  }

  .booking-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: 28px;
  }

  .booking-actions .btn {
    min-width: 200px;
    border-radius: 999px;
  }

  .booking-list-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
  }

  .booking-summary-cards {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 26px;
  }

  .booking-summary-card {
    padding: 24px;
  }

  .booking-summary-card h3 {
    margin-bottom: 12px;
    font-size: 1.1rem;
    font-weight: 700;
    color: #111827;
  }

  .booking-summary-card p {
    margin-bottom: 0;
    color: #6b7280;
    line-height: 1.7;
  }

  .booking-summary-list {
    display: grid;
    gap: 14px;
  }

  .booking-summary-list__row {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    padding: 16px 18px;
    border-radius: 18px;
    background: #f8fafc;
    color: #475569;
  }

  .booking-summary-list__row strong {
    color: #111827;
  }

  .destination-page-shell {
    padding-top: 56px;
    padding-bottom: 56px;
    background:
      radial-gradient(circle at top center, rgba(130, 198, 141, 0.08), transparent 34%),
      linear-gradient(180deg, #fbfbf8 0%, #ffffff 100%);
  }

  .destination-toolbar {
    display: flex;
    justify-content: space-between;
    gap: 18px;
    align-items: flex-start;
    margin-bottom: 28px;
  }

  .destination-toolbar__copy h1 {
    margin-bottom: 10px;
    font-size: 2rem;
    font-weight: 800;
    color: #111827;
  }

  .destination-toolbar__copy p {
    margin: 0;
    max-width: 760px;
    color: #6b7280;
    line-height: 1.8;
  }

  .destination-toolbar__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
  }

  .destination-filter {
    display: grid;
    grid-template-columns: 1.2fr .9fr .8fr auto;
    gap: 14px;
    padding: 18px;
    border-radius: 24px;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
    margin-bottom: 28px;
  }

  .destination-filter__field label {
    display: block;
    margin-bottom: 8px;
    font-weight: 700;
    color: #334155;
  }

  .destination-filter__field input,
  .destination-filter__field select {
    min-height: 52px;
    border-radius: 16px;
    border: 1px solid #d9dee7;
  }

  .destination-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 24px;
  }

  .destination-card {
    padding: 18px;
    border-radius: 28px;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: 0 22px 55px rgba(15, 23, 42, 0.08);
    height: 100%;
  }

  .destination-card__image {
    border-radius: 22px;
    background: linear-gradient(180deg, #faf8f6 0%, #ffffff 100%);
    padding: 20px;
    height: 240px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }

  .destination-card__image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
  }

  .destination-card__body {
    padding: 18px 8px 8px;
  }

  .destination-card__meta {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: center;
    margin-bottom: 18px;
  }

  .destination-card__location,
  .destination-card__price {
    display: inline-flex;
    align-items: center;
    padding: 8px 14px;
    border-radius: 999px;
    font-size: 0.82rem;
    line-height: 1;
  }

  .destination-card__location {
    background: #f4f5f7;
    color: #64748b;
  }

  .destination-card__price {
    background: #182033;
    color: #ffffff;
    font-weight: 700;
  }

  .destination-card__title {
    margin-bottom: 10px;
    font-size: 1.55rem;
    font-weight: 800;
    color: #111827;
    line-height: 1.25;
  }

  .destination-card__description {
    margin-bottom: 20px;
    color: #6b7280;
    line-height: 1.8;
    min-height: 72px;
  }

  .destination-card__details {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
    margin-bottom: 18px;
  }

  .destination-card__detail {
    padding: 10px 12px;
    border-radius: 16px;
    background: #f8fafc;
    border: 1px solid rgba(148, 163, 184, 0.16);
  }

  .destination-card__detail span {
    display: block;
    margin-bottom: 4px;
    font-size: 0.74rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    font-weight: 700;
  }

  .destination-card__detail strong {
    display: block;
    color: #334155;
    font-size: 0.9rem;
    line-height: 1.45;
  }

  .destination-card__select {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    padding: 18px;
    border-radius: 22px;
    border: 1px solid #d9dee7;
    background: #fbfbfc;
    margin-bottom: 18px;
    cursor: pointer;
  }

  .destination-card__select.is-active {
    border-color: rgba(130, 198, 141, 0.72);
    background: #f7fcf5;
    box-shadow: 0 16px 36px rgba(130, 198, 141, 0.12);
  }

  .destination-card__status {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-top: 12px;
  }

  .destination-card__status-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(130, 198, 141, 0.16);
    color: #2f6d39;
    font-size: 0.8rem;
    font-weight: 700;
    opacity: 0;
    transform: translateY(4px);
    transition: opacity 0.2s ease, transform 0.2s ease;
  }

  .destination-card__status-badge.is-visible {
    opacity: 1;
    transform: translateY(0);
  }

  .destination-card__subtotal {
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 600;
  }

  .destination-card__subtotal strong {
    color: #111827;
  }

  .destination-card__select input {
    margin-top: 5px;
  }

  .destination-card__select strong {
    display: block;
    margin-bottom: 6px;
    color: #374151;
    font-weight: 700;
  }

  .destination-card__select small {
    color: #9aa4b2;
    line-height: 1.7;
  }

  .destination-card__qty label {
    display: block;
    margin-bottom: 10px;
    font-weight: 700;
    color: #334155;
  }

  .destination-card__qty input {
    border: 0;
    box-shadow: none;
    text-align: center;
    font-size: 1.05rem;
    font-weight: 700;
    color: #111827;
    background: transparent;
    min-height: auto;
    padding: 0;
  }

  .destination-card__buy-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    border: 0;
    border-radius: 999px;
    background: #87b83e;
    color: #ffffff;
    font-weight: 700;
    min-height: 52px;
    transition: background 0.2s ease, transform 0.2s ease;
  }

  .destination-card__buy-btn:hover,
  .destination-card__buy-btn:focus {
    background: #769f35;
    color: #ffffff;
    transform: translateY(-1px);
  }

  .destination-card__buy-btn.is-active {
    background: #182033;
  }

  .destination-stepper {
    display: grid;
    grid-template-columns: 56px 1fr 56px;
    align-items: center;
    gap: 10px;
    min-height: 62px;
    padding: 6px;
    border-radius: 20px;
    border: 1px solid #d9dee7;
    background: #ffffff;
  }

  .destination-stepper__btn {
    width: 44px;
    height: 44px;
    border: 0;
    border-radius: 14px;
    background: #eef2f7;
    color: #111827;
    font-size: 1.35rem;
    font-weight: 700;
    line-height: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .destination-stepper__btn:hover,
  .destination-stepper__btn:focus {
    background: #dfe6ee;
  }

  .destination-stepper__value {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .destination-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 18px;
    padding: 22px 24px;
    border-radius: 26px;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: 0 22px 55px rgba(15, 23, 42, 0.08);
    margin-top: 30px;
  }

  .destination-summary.is-sticky {
    position: sticky;
    bottom: 18px;
    z-index: 20;
  }

  .destination-summary__stats {
    display: flex;
    gap: 28px;
    flex-wrap: wrap;
  }

  .destination-summary__stats strong {
    display: block;
    font-size: 1.15rem;
    color: #111827;
  }

  .destination-summary__stats span {
    color: #6b7280;
    font-size: 0.9rem;
  }

  .destination-empty {
    padding: 36px 28px;
    border-radius: 26px;
    background: #ffffff;
    border: 1px dashed rgba(15, 23, 42, 0.14);
    text-align: center;
    color: #64748b;
  }

  .catalog-shell {
    padding-top: 56px;
    padding-bottom: 56px;
    background:
      radial-gradient(circle at top center, rgba(130, 198, 141, 0.08), transparent 34%),
      linear-gradient(180deg, #fbfbf8 0%, #ffffff 100%);
  }

  .catalog-toolbar {
    display: flex;
    justify-content: space-between;
    gap: 18px;
    align-items: flex-start;
    margin-bottom: 28px;
  }

  .catalog-toolbar__copy h1 {
    margin-bottom: 10px;
    font-size: 2rem;
    font-weight: 800;
    color: #111827;
  }

  .catalog-toolbar__copy p {
    margin: 0;
    max-width: 760px;
    color: #6b7280;
    line-height: 1.8;
  }

  .catalog-toolbar__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
  }

  .catalog-filter {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 26px;
  }

  .catalog-filter__btn {
    border: 1px solid rgba(15, 23, 42, 0.1);
    background: #ffffff;
    color: #334155;
    padding: 12px 18px;
    border-radius: 999px;
    font-weight: 700;
    line-height: 1;
  }

  .catalog-filter__btn.is-active {
    background: #182033;
    border-color: #182033;
    color: #ffffff;
  }

  .catalog-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 24px;
  }

  .catalog-card {
    padding: 18px;
    border-radius: 28px;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: 0 22px 55px rgba(15, 23, 42, 0.08);
    height: 100%;
  }

  .catalog-card__image {
    border-radius: 22px;
    background: linear-gradient(180deg, #faf8f6 0%, #ffffff 100%);
    padding: 20px;
    height: 240px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }

  .catalog-card__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 18px;
  }

  .catalog-card__body {
    padding: 18px 8px 8px;
  }

  .catalog-card__meta {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: center;
    margin-bottom: 18px;
  }

  .catalog-card__tag,
  .catalog-card__price {
    display: inline-flex;
    align-items: center;
    padding: 8px 14px;
    border-radius: 999px;
    font-size: 0.82rem;
    line-height: 1;
  }

  .catalog-card__tag {
    background: #f4f5f7;
    color: #64748b;
    text-transform: capitalize;
  }

  .catalog-card__price {
    background: #182033;
    color: #ffffff;
    font-weight: 700;
  }

  .catalog-card__title {
    margin-bottom: 8px;
    font-size: 1.45rem;
    font-weight: 800;
    color: #111827;
    line-height: 1.25;
  }

  .catalog-card__subtitle {
    margin-bottom: 10px;
    color: #64748b;
    font-weight: 600;
  }

  .catalog-card__description {
    margin-bottom: 18px;
    color: #6b7280;
    line-height: 1.8;
    min-height: 72px;
  }

  .catalog-card__highlight {
    margin-bottom: 16px;
    color: #2f6d39;
    font-weight: 700;
  }

  .catalog-card__details {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
    margin-bottom: 16px;
  }

  .catalog-card__detail {
    padding: 10px 12px;
    border-radius: 16px;
    background: #f8fafc;
    border: 1px solid rgba(148, 163, 184, 0.16);
  }

  .catalog-card__detail span {
    display: block;
    margin-bottom: 4px;
    font-size: 0.74rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    font-weight: 700;
  }

  .catalog-card__detail strong {
    display: block;
    color: #334155;
    font-size: 0.9rem;
    line-height: 1.45;
  }

  .catalog-card__amenities {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 16px;
  }

  .catalog-card__amenity {
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    border-radius: 999px;
    background: #f4f5f7;
    color: #475569;
    font-size: 0.82rem;
    line-height: 1;
  }

  .catalog-card__choice {
    padding: 18px;
    border-radius: 22px;
    border: 1px solid #d9dee7;
    background: #fbfbfc;
    margin-bottom: 18px;
  }

  .catalog-card__choice.is-active {
    border-color: rgba(130, 198, 141, 0.72);
    background: #f7fcf5;
    box-shadow: 0 16px 36px rgba(130, 198, 141, 0.12);
  }

  .catalog-card__status {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-top: 12px;
  }

  .catalog-card__status-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(130, 198, 141, 0.16);
    color: #2f6d39;
    font-size: 0.8rem;
    font-weight: 700;
    opacity: 0;
    transform: translateY(4px);
    transition: opacity 0.2s ease, transform 0.2s ease;
  }

  .catalog-card__status-badge.is-visible {
    opacity: 1;
    transform: translateY(0);
  }

  .catalog-card__choice small {
    color: #9aa4b2;
    line-height: 1.7;
  }

  .catalog-card__buy-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    border: 0;
    border-radius: 999px;
    background: #87b83e;
    color: #ffffff;
    font-weight: 700;
    min-height: 52px;
  }

  .catalog-card__buy-btn.is-active {
    background: #182033;
  }

  .catalog-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 18px;
    padding: 22px 24px;
    border-radius: 26px;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: 0 22px 55px rgba(15, 23, 42, 0.08);
    margin-top: 30px;
  }

  .catalog-summary__stats {
    display: flex;
    gap: 28px;
    flex-wrap: wrap;
  }

  .catalog-summary__stats strong {
    display: block;
    font-size: 1.15rem;
    color: #111827;
  }

  .catalog-summary__stats span {
    color: #6b7280;
    font-size: 0.9rem;
  }

  .transport-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
  }

  .transport-card {
    padding: 22px;
    border-radius: 28px;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: 0 22px 55px rgba(15, 23, 42, 0.08);
    height: 100%;
  }

  .transport-card.is-active {
    border-color: rgba(130, 198, 141, 0.72);
    background: #f9fef8;
    box-shadow: 0 22px 55px rgba(130, 198, 141, 0.14);
  }

  .transport-card__header {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    align-items: flex-start;
    margin-bottom: 16px;
  }

  .transport-card__title {
    margin-bottom: 8px;
    font-size: 1.35rem;
    font-weight: 800;
    color: #111827;
  }

  .transport-card__desc {
    margin: 0;
    color: #6b7280;
    line-height: 1.8;
  }

  .transport-card__price {
    display: inline-flex;
    align-items: center;
    padding: 9px 14px;
    border-radius: 999px;
    background: #182033;
    color: #ffffff;
    font-size: 0.84rem;
    font-weight: 700;
    line-height: 1;
    white-space: nowrap;
  }

  .transport-card__choose {
    margin-top: 18px;
  }

  .transport-card__choose .btn {
    width: 100%;
    border-radius: 999px;
  }

  .transport-card__choose .btn.is-active {
    background: #182033;
    border-color: #182033;
    color: #ffffff;
  }

  .transport-detail-panel {
    margin-top: 30px;
    padding: 28px;
    border-radius: 28px;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: 0 22px 55px rgba(15, 23, 42, 0.08);
  }

  .transport-detail-panel__title {
    margin-bottom: 8px;
    font-size: 1.35rem;
    font-weight: 800;
    color: #111827;
  }

  .transport-detail-panel__desc {
    margin-bottom: 22px;
    color: #6b7280;
    line-height: 1.8;
  }

  @media (max-width: 991.98px) {
    .booking-progress,
    .booking-summary-cards,
    .booking-list-grid,
    .destination-grid,
    .catalog-grid,
    .transport-grid {
      grid-template-columns: 1fr;
    }

    .booking-panel,
    .booking-summary {
      padding: 24px;
    }

    .booking-summary {
      position: static;
      margin-top: 22px;
    }

    .booking-actions .btn {
      width: 100%;
    }

    .destination-filter {
      grid-template-columns: 1fr;
    }

    .destination-card__details {
      grid-template-columns: 1fr;
    }

    .catalog-card__details {
      grid-template-columns: 1fr;
    }

    .destination-toolbar,
    .destination-summary,
    .catalog-toolbar,
    .catalog-summary {
      flex-direction: column;
      align-items: stretch;
    }

    .destination-summary.is-sticky {
      position: static;
    }
  }
</style>
