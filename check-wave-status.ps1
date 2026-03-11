# Script de diagnostic Wave Payment
Write-Host "=== DIAGNOSTIC WAVE PAYMENT ===" -ForegroundColor Cyan

Write-Host "`n1. Configuration actuelle:" -ForegroundColor Yellow
php artisan config:show services | Select-String "wave"

Write-Host "`n2. URLs configurées:" -ForegroundColor Yellow
Get-Content .env | Select-String "WAVE_|APP_URL"

Write-Host "`n3. Transactions Wave dans la base:" -ForegroundColor Yellow
php artisan tinker --execute="\$count = \App\Models\WaveTransaction::count(); echo 'Total transactions: ' . \$count . PHP_EOL; if(\$count > 0) { \$latest = \App\Models\WaveTransaction::latest()->first(); echo 'Dernière transaction: ' . \$latest->transaction_ref . ' - Status: ' . \$latest->status . PHP_EOL; }"

Write-Host "`n4. Dernières erreurs Wave dans les logs:" -ForegroundColor Yellow
Get-Content storage/logs/laravel.log -Tail 100 | Select-String "Wave.*Error|Wave.*Failed" -Context 1 | Select-Object -Last 5

Write-Host "`n5. Test de session:" -ForegroundColor Yellow
php artisan tinker --execute="session(['test_wave' => 'ok']); echo session('test_wave') ? 'Session OK' : 'Session KO'; echo PHP_EOL;"

Write-Host "`n=== RÉSUMÉ ===" -ForegroundColor Cyan
Write-Host "- Si vous voyez 'URL scheme not permitted' dans les logs:" -ForegroundColor Red
Write-Host "  → Wave exige HTTPS. Utilisez ngrok (voir NGROK_SETUP.md)" -ForegroundColor Red
Write-Host "`n- Si 'Total transactions: 0':" -ForegroundColor Red
Write-Host "  → Aucun paiement Wave n'a été initié avec succès" -ForegroundColor Red
Write-Host "`n- Si une transaction existe avec status 'pending':" -ForegroundColor Yellow
Write-Host "  → Le paiement a été initié mais le webhook n'a pas reçu la confirmation" -ForegroundColor Yellow

Write-Host "`n Pour tester Wave en local, suivez: NGROK_SETUP.md" -ForegroundColor Green
