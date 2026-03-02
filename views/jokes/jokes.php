<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card bg-dark border-0 shadow-lg rounded-4 text-light">
                <div class="card-body p-4">

                    <h3 class="mb-4 text-center fw-bold">
                        🎭 Jokes List
                    </h3>

                    <div class="jokes-container mb-4">
                        <ul class="list-unstyled mb-0">
                            <?php if (!empty($jokes)): ?>
                                <?php if (isset($jokes['jokes'])): ?>
                                    <?php foreach ($jokes['jokes'] as $joke): ?>
                                        <li class="joke-item mb-3 p-3 rounded-3">

                                            <?php if ($joke['type'] === 'single'): ?>
                                                <p class="mb-2 fs-5">
                                                    <?= htmlspecialchars($joke['joke']) ?>
                                                </p>

                                            <?php elseif ($joke['type'] === 'twopart'): ?>

                                                <p class="mb-2 fs-5">
                                                    <?= htmlspecialchars($joke['setup']) ?>
                                                </p>

                                                <div class="delivery mt-2">
                                                    <?= htmlspecialchars($joke['delivery']) ?>
                                                </div>

                                                <button type="button"
                                                    class="btn btn-sm btn-outline-info mt-3 reveal-btn">
                                                    Reveal punchline
                                                </button>

                                            <?php endif; ?>

                                            <div class="mt-3 small text-muted border-top pt-2">
                                                <?= htmlspecialchars($joke['category']) ?> •
                                                <?= htmlspecialchars($joke['lang']) ?>
                                            </div>

                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="joke-item mb-3 p-3 rounded-3">
                                        <?php if ($jokes['type'] === 'single'): ?>
                                            <p class="mb-2 fs-5">
                                                <?= htmlspecialchars($jokes['joke']) ?>
                                            </p>
                                        <?php elseif ($jokes['type'] === 'twopart'): ?>
                                            <p class="mb-2 fs-5">
                                                <?= htmlspecialchars($jokes['setup']) ?>
                                            </p>
                                            <div class="delivery mt-2">
                                                <?= htmlspecialchars($jokes['delivery']) ?>
                                            </div>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-info mt-3 reveal-btn">
                                                Reveal punchline
                                            </button>
                                        <?php endif; ?>

                                        <div class="mt-3 small text-muted border-top pt-2">
                                            <?= htmlspecialchars($jokes['category']) ?> •
                                            <?= htmlspecialchars($jokes['lang']) ?>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            <?php else: ?>
                                <li class="text-center py-4">
                                    No jokes yet! 🤷
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4 d-grid">
                            <form method="post" action="/jokes/new" class="d-flex justify-content-start">
                                <button type="submit" class="btn btn-outline-primary rounded-3">
                                    New Jokes
                                </button>
                            </form>
                        </div>
                        <div class="col-md-4 d-grid">
                            <form method="post" action="/jokes/clear" class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-outline-danger rounded-3">
                                    Clear List
                                </button>
                            </form>
                        </div>
                        <div class="col-md-4 d-grid">
                            <form action="/settings" class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-outline-warning rounded-3">
                                    Settings
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.reveal-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const delivery = this.parentElement.querySelector('.delivery');
                delivery.style.display = 'block';
                delivery.style.opacity = 0;
                delivery.style.transition = "opacity 0.5s";
                setTimeout(() => delivery.style.opacity = 1, 10);
                this.style.display = 'none';
            });
        });
    });
</script>
