<div class="container py-5">
    <form method="POST" action="/settings/update">

        <div class="card bg-dark text-light border-secondary shadow">
            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-4">
                        <div class="border border-secondary rounded p-3 h-100">
                            <h6 class="text-secondary">Category</h6>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category_mode" value="any"
                                    <?= $settings->category_any ? 'checked' : '' ?>>
                                <label class="form-check-label">Any</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="category_mode" value="custom"
                                    <?= !$settings->category_any ? 'checked' : '' ?>>
                                <label class="form-check-label">Custom</label>
                            </div>

                            <hr class="border-secondary">

                            <?php foreach ($categories as $field => $label): ?>
                                <div class="form-check">
                                    <input class="form-check-input category-checkbox" type="checkbox" name="<?= $field ?>"
                                        value="1" <?= $settings->$field ? 'checked' : '' ?>>
                                    <label class="form-check-label"><?= $label ?></label>
                                </div>
                            <?php endforeach; ?>
                            <div id="categoryError" class="text-danger small mt-2 d-none">
                                Please select at least one category.
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border border-secondary rounded p-3 mb-4">
                            <h6 class="text-secondary">Language</h6>

                            <select name="language_code" class="form-select bg-dark text-light border-secondary">
                                <option value="cs" <?= $settings->language_code === 'cs' ? 'selected' : '' ?>>Čeština
                                </option>
                                <option value="de" <?= $settings->language_code === 'de' ? 'selected' : '' ?>>Deutsch
                                </option>
                                <option value="en" <?= $settings->language_code === 'en' ? 'selected' : '' ?>>English
                                </option>
                                <option value="es" <?= $settings->language_code === 'es' ? 'selected' : '' ?>>Español
                                </option>
                                <option value="fr" <?= $settings->language_code === 'fr' ? 'selected' : '' ?>>Français
                                </option>
                                <option value="pt" <?= $settings->language_code === 'pt' ? 'selected' : '' ?>>Português
                                </option>
                            </select>
                        </div>

                        <div class="border border-secondary rounded p-3">
                            <h6 class="text-secondary">Blacklist</h6>

                            <?php foreach ($blacklists as $field => $label): ?>
                                <div class="form-check">
                                    <input class="form-check-input blacklist-checkbox"
                                        type="checkbox"
                                        name="<?= $field ?>"
                                        value="1"
                                        <?= $settings->$field ? 'checked' : '' ?>>
                                    <label class="form-check-label"><?= $label ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border border-secondary rounded p-3 mb-4">
                            <h6 class="text-secondary">Joke type</h6>

                            <div class="form-check">
                                <input class="form-check-input joke-checkbox" type="checkbox" name="allow_single"
                                    value="1" <?= $settings->allow_single ? 'checked' : '' ?>>
                                <label class="form-check-label">Single</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input joke-checkbox" type="checkbox" name="allow_two_part"
                                    value="1" <?= $settings->allow_two_part ? 'checked' : '' ?>>
                                <label class="form-check-label">Two part</label>
                            </div>

                            <small class="text-secondary">Min one checked</small>
                            <div id="jokeTypeError" class="text-danger small mt-2 d-none">
                                Please select at least one joke type.
                            </div>

                        </div>

                        <div class="border border-secondary rounded p-3 mb-4">
                            <h6 class="text-secondary">Number of jokes</h6>

                            <input type="number" name="joke_amount" min="1" max="10"
                                value="<?= $settings->joke_amount ?>"
                                class="form-control bg-dark text-light border-secondary">
                        </div>

                        <div class="border border-secondary rounded p-3">

                            <input type="checkbox" name="safe_mode" id="safe_mode" <?= $settings->safe_mode ? 'checked' : '' ?>>
                            <label for="safe_mode" class="form-check-label">Safe Mode</label>
                        </div>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col text-start">
                        <button formaction="/settings/reset" class="btn btn-outline-warning">
                            Reset
                        </button>
                    </div>

                    <div class="col text-center">
                        <button type="submit" id="submitBtn" class="btn btn-outline-primary px-5" disabled>
                            Submit
                        </button>
                    </div>

                    <div class="col text-end">
                        <a href="/" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </div>

            </div>
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const anyRadio = document.querySelector('input[name="category_mode"][value="any"]');
        const customRadio = document.querySelector('input[name="category_mode"][value="custom"]');
        const categoryCheckboxes = document.querySelectorAll('.category-checkbox');

        const jokeCheckboxes = document.querySelectorAll('.joke-checkbox');

        const categoryError = document.getElementById('categoryError');
        const jokeTypeError = document.getElementById('jokeTypeError');
        const submitBtn = document.getElementById('submitBtn');

        const safeModeCheckbox = document.getElementById('safe_mode');
        const blacklistCheckboxes = document.querySelectorAll('.blacklist-checkbox');

        function updateBlacklistState() {
            if (safeModeCheckbox.checked) {
                blacklistCheckboxes.forEach(cb => {
                    cb.checked = true;
                    cb.disabled = true;
                });
            } else {
                blacklistCheckboxes.forEach(cb => {
                    cb.disabled = false;
                });
            }
        }

        function updateCategoryState() {
            if (anyRadio.checked) {
                categoryCheckboxes.forEach(cb => {
                    cb.checked = false;
                    cb.disabled = true;
                });
            } else {
                categoryCheckboxes.forEach(cb => {
                    cb.disabled = false;
                });
            }
        }

        function validateCategories() {
            if (customRadio.checked) {
                updateCategoryState();
                const valid = [...categoryCheckboxes].some(cb => cb.checked);
                categoryError.classList.toggle('d-none', valid);
                return valid;
            }

            categoryError.classList.add('d-none');
            return true;
        }

        function validateJokeType() {
            const valid = [...jokeCheckboxes].some(cb => cb.checked);
            jokeTypeError.classList.toggle('d-none', valid);
            return valid;
        }

        function validateForm() {
            const categoriesValid = validateCategories();
            const jokeTypeValid = validateJokeType();

            submitBtn.disabled = !(categoriesValid && jokeTypeValid);
        }

        const success = <?= json_encode($_SESSION['success'] ?? null) ?>;

        if (success) {
            setTimeout(() => {
                submitBtn.classList.add('btn-success');
                submitBtn.classList.remove('btn-outline-primary');
            }, 100);

            setTimeout(() => {
                submitBtn.classList.remove('btn-success');
                submitBtn.classList.add('btn-outline-primary');
                window.location.href = '/settings';
            }, 1000);
        }

        document.querySelectorAll(
            'input[name="category_mode"], .category-checkbox, .joke-checkbox'
        ).forEach(el => {
            el.addEventListener('change', validateForm);
        });

        anyRadio.addEventListener('change', () => {
            updateCategoryState();
            validateForm();
        });

        customRadio.addEventListener('change', () => {
            updateCategoryState();
            validateForm();
        });

        safeModeCheckbox.addEventListener('change', () => {
            updateBlacklistState();
        });

        // Initial validation
        updateCategoryState();
        updateBlacklistState();
        validateForm();
    });
</script>

<?php unset($_SESSION['success']); ?>