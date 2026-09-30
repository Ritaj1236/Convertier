<?php
$labels = ['dz' => 'DZD', 'euro' => 'EUR', 'dol' => 'USD'];
$symbols = ['dz' => 'DA', 'euro' => '€', 'dol' => '$'];
$rates = ['dz' => 1, 'euro' => 280, 'dol' => 240]; // valeur de 1 unité en DZ

$somme = $_POST['somme'] ?? '';
$d1 = $_POST['d1'] ?? 'dz';
$d2 = $_POST['d2'] ?? 'euro';
$result = null;
$error = null;

if (isset($_POST['c'])) {
    if ($somme === '' || !is_numeric($somme)) {
        $error = "Entrez un montant.";
    } elseif (!isset($rates[$d1]) || !isset($rates[$d2])) {
        $error = "Choisissez les deux devises.";
    } else {
        $result = $d1 === $d2 ? $somme : $somme * $rates[$d1] / $rates[$d2];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Convertisseur de devises</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;600;800&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #e9efe9;
    --card: #ffffff;
    --ink: #10281f;
    --muted: #5d7268;
    --line: #cfdbd2;
    --green: #0f4a36;
    --green-soft: #dcebe1;
    --gold: #f2b632;
  }
  * { box-sizing: border-box; }
  html, body { margin: 0; min-height: 100%; }
  body {
    font-family: "Bricolage Grotesque", system-ui, sans-serif;
    background: var(--bg);
    color: var(--ink);
    display: grid;
    place-items: center;
    padding: 24px;
    min-height: 100vh;
  }
  .card {
    width: 100%;
    max-width: 440px;
    background: var(--card);
    border-radius: 24px;
    padding: 32px 28px;
    box-shadow: 0 20px 50px -24px rgba(15, 74, 54, .45);
  }
  h1 { font-size: 1.6rem; font-weight: 800; margin: 0 0 4px; letter-spacing: -.02em; }
  .sub { margin: 0 0 24px; color: var(--muted); font-size: .95rem; }

  .amount {
    width: 100%;
    font: inherit;
    font-size: 2.2rem;
    font-weight: 600;
    padding: 14px 16px;
    border: 2px solid var(--line);
    border-radius: 14px;
    background: #f8fbf8;
    color: var(--ink);
  }
  .amount:focus { outline: 3px solid var(--gold); border-color: var(--green); }

  fieldset { border: 0; padding: 0; margin: 22px 0 0; }
  legend { font-size: .9rem; font-weight: 600; color: var(--muted); padding: 0; margin-bottom: 8px; }
  .seg { display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; background: var(--green-soft); padding: 5px; border-radius: 14px; }
  .seg input { position: absolute; opacity: 0; pointer-events: none; }
  .seg label {
    text-align: center;
    padding: 12px 0;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    color: var(--green);
    transition: background .15s, color .15s;
  }
  .seg label small { display: block; font-weight: 400; font-size: .72rem; opacity: .75; }
  .seg input:checked + label { background: var(--green); color: #fff; }
  .seg input:focus-visible + label { outline: 3px solid var(--gold); outline-offset: 2px; }

  button {
    width: 100%;
    margin-top: 26px;
    font: inherit;
    font-weight: 800;
    font-size: 1.05rem;
    padding: 16px;
    border: 0;
    border-radius: 14px;
    background: var(--gold);
    color: var(--ink);
    cursor: pointer;
  }
  button:hover { filter: brightness(.96); }
  button:focus-visible { outline: 3px solid var(--green); outline-offset: 2px; }

  .result {
    margin-top: 24px;
    padding: 20px;
    border-radius: 16px;
    background: var(--green);
    color: #fff;
  }
  .result .from { font-size: .9rem; opacity: .8; }
  .result .value { font-size: 2.4rem; font-weight: 800; letter-spacing: -.02em; word-break: break-all; }
  .result .value span { font-size: 1.1rem; font-weight: 600; margin-left: 6px; color: var(--gold); }
  .error { margin-top: 18px; padding: 12px 14px; border-radius: 12px; background: #fde8e4; color: #8a2b1c; font-weight: 600; }
  @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
</style>
</head>
<body>
  <main class="card">
    <h1>Convertisseur de devises</h1>
    <p class="sub">Dinar, euro et dollar en un clic.</p>

    <form method="post">
      <input class="amount" type="number" name="somme" step="any" placeholder="Montant"
             value="<?= htmlspecialchars($somme) ?>" aria-label="Montant" required>

      <fieldset>
        <legend>De</legend>
        <div class="seg">
          <?php foreach ($labels as $key => $code): ?>
            <input type="radio" name="d1" id="d1-<?= $key ?>" value="<?= $key ?>" <?= $d1 === $key ? 'checked' : '' ?>>
            <label for="d1-<?= $key ?>"><?= $symbols[$key] ?><small><?= $code ?></small></label>
          <?php endforeach; ?>
        </div>
      </fieldset>

      <fieldset>
        <legend>Vers</legend>
        <div class="seg">
          <?php foreach ($labels as $key => $code): ?>
            <input type="radio" name="d2" id="d2-<?= $key ?>" value="<?= $key ?>" <?= $d2 === $key ? 'checked' : '' ?>>
            <label for="d2-<?= $key ?>"><?= $symbols[$key] ?><small><?= $code ?></small></label>
          <?php endforeach; ?>
        </div>
      </fieldset>

      <button type="submit" name="c" value="convertir">Convertir</button>
    </form>

    <?php if ($error): ?>
      <div class="error"><?= $error ?></div>
    <?php elseif ($result !== null): ?>
      <div class="result" aria-live="polite">
        <div class="from"><?= htmlspecialchars($somme) ?> <?= $labels[$d1] ?> =</div>
        <div class="value"><?= number_format($result, 2, ',', ' ') ?><span><?= $labels[$d2] ?></span></div>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>