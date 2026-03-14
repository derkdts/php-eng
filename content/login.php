<?php
$error = $_SESSION['error'] ?? null;
$successReset = $_SESSION['success_reset'] ?? null;
unset($_SESSION['error'], $_SESSION['success_reset']);
?>

<div class="login-container">
    <div class="login-card card">
        <div class="login-header">
            <div class="login-icon-glow">
                <span class="login-emoji">🔐</span>
            </div>
            <h2>Авторизация</h2>
            <p>Добро пожаловать в English Prep</p>
        </div>

        <?php if ($error): ?>
            <div class="alert error fade-in">
                <?= h($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($successReset): ?>
            <div class="alert success fade-in">
                <p><strong>Данные успешно сброшены!</strong></p>
            </div>
        <?php endif; ?>

        <div id="login-section">
            <form action="" method="POST" class="login-form">
                <input type="hidden" name="action" value="login">

                <div class="form-group">
                    <label for="username">Имя пользователя</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" class="glass-input" required autofocus
                            placeholder="Введите логин" autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Пароль</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="glass-input" required
                            placeholder="Введите пароль" autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="primary-btn btn-block" style="margin-top: 2rem;">
                    Войти в систему
                </button>
            </form>

            <form action="" method="POST" id="reset-form">
                <input type="hidden" name="action" value="reset_credentials">
                <div class="divider">
                    <span>или</span>
                </div>
                <div style="text-align: center;">
                    <button type="submit" class="btn-link">Забыли данные?</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    <?php if ($successReset): ?>
            (function () {
                const user = "<?= h($successReset['user']) ?>";
                const pass = "<?= h($successReset['pass']) ?>";
                const content = `English Prep - Данные для входа\n\nЛогин: ${user}\nПароль: ${pass}\nДата: ${new Date().toLocaleString()}`;

                const blob = new Blob([content], { type: 'text/plain' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'english_prep_access.txt';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            })();
    <?php endif; ?>
</script>

<style>
    /* Existing styles... */

    .divider {
        margin: 2rem 0;
        position: relative;
        text-align: center;
    }

    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: rgba(255, 255, 255, 0.05);
    }

    .divider span {
        position: relative;
        background: var(--card-bg);
        padding: 0 1rem;
        color: var(--text-secondary);
        font-size: 0.8rem;
        text-transform: uppercase;
    }

    .btn-link {
        background: none;
        border: none;
        color: var(--accent-color);
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 0;
        margin-bottom: 1rem;
        transition: var(--transition-smooth);
    }

    .btn-link:hover {
        color: #fff;
        text-decoration: underline;
    }

    .reset-form {
        margin-top: 1rem;
        animation: fadeIn 0.4s ease-out;
    }

    .reset-hint {
        color: var(--text-secondary);
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 120px);
        padding: 20px;
        animation: fadeIn 0.8s ease-out;
    }

    .login-card {
        width: 100%;
        max-width: 440px;
        padding: 3rem;
        border-radius: var(--border-radius-md);
        text-align: center;
        background: var(--card-bg);
        backdrop-filter: blur(40px) saturate(180%);
    }

    .login-header {
        margin-bottom: 2.5rem;
    }

    .login-icon-glow {
        width: 80px;
        height: 80px;
        background: var(--accent-glow);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 0 30px var(--accent-glow);
        animation: pulseIcon 3s infinite alternate ease-in-out;
    }

    .login-emoji {
        font-size: 2.5rem;
    }

    .login-header h2 {
        font-size: 2rem;
        margin-bottom: 0.75rem;
        font-weight: 700;
        background: linear-gradient(to right, #fff, #94a3b8);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .login-header p {
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .login-form {
        text-align: left;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.75rem;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-secondary);
    }

    .btn-block {
        width: 100%;
    }

    .alert {
        padding: 1rem;
        border-radius: var(--border-radius-sm);
        margin-bottom: 2rem;
        font-size: 0.95rem;
        text-align: center;
    }

    .alert.error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #fca5a5;
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.1);
    }

    .alert.success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.2);
        color: #86efac;
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.1);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulseIcon {
        0% {
            transform: scale(1);
            box-shadow: 0 0 20px var(--accent-glow);
        }

        100% {
            transform: scale(1.05);
            box-shadow: 0 0 40px var(--accent-glow);
        }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }
</style>