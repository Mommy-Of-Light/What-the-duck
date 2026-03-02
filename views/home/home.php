<div class="container py-5 random-joke-section">

    <div class="text-center mb-5">
        <h1 class="fw-bold display-5 gradient-title">
            🎭 Random Jokes
        </h1>
        <p class="text-muted text-light">A bit of humor to brighten your day</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="joke-card p-5 text-center">

                <?php if ($joke['type'] === 'single'): ?>

                    <p class="joke-text">
                        <?= htmlspecialchars($joke['joke']) ?>
                    </p>

                <?php elseif ($joke['type'] === 'twopart'): ?>

                    <p class="joke-text mb-4">
                        <?= htmlspecialchars($joke['setup']) ?>
                    </p>

                    <div id="delivery" class="delivery-text">
                        <?= htmlspecialchars($joke['delivery']) ?>
                    </div>

                    <button type="button" id="revealBtn" class="btn btn-gradient mt-4">
                        Reveal punchline
                    </button>

                <?php endif; ?>

                <div class="mt-4">
                    <button type="button" id="reloadBtn" class="btn btn-reload" onclick="location.reload()">
                        🔄 New Joke
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btn = document.getElementById("revealBtn");
        if (btn) {
            btn.addEventListener("click", function() {
                const delivery = document.getElementById("delivery");
                delivery.style.display = "block";
                setTimeout(() => delivery.style.opacity = 1, 50);
                this.style.display = "none";
            });
        }

        const reloadBtn = document.getElementById("reloadBtn");

        if (reloadBtn) {
            reloadBtn.addEventListener("click", function() {

                // Prevent multiple clicks
                reloadBtn.disabled = true;

                // Add spinning effect
                reloadBtn.classList.add("spin");

                // Small delay for smooth UX
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            });
        }

    });
</script>
