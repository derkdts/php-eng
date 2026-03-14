<?php
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
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

        <form action="" method="POST" class="login-form">
            <input type="hidden" name="action" value="login">
            
            <div class="form-group">
                <label for="username">Имя пользователя</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" class="glass-input" required autofocus placeholder="Введите логин" autocomplete="username">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Пароль</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="glass-input" required placeholder="Введите пароль" autocomplete="current-password">
                </div>
            </div>

            <button type="submit" class="primary-btn btn-block" style="margin-top: 2rem;">
                Войти в систему
            </button>
        </form>
    </div>
</div>

<style>
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

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes pulseIcon {
    0% { transform: scale(1); box-shadow: 0 0 20px var(--accent-glow); }
    100% { transform: scale(1.05); box-shadow: 0 0 40px var(--accent-glow); }
}

.fade-in {
    animation: fadeIn 0.5s ease-out;
}
</style>
