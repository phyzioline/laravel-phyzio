# Verify the fix is on the server
Write-Host "Checking if bootstrap/app.php has the fix..."

ssh phyziolinegit@147.93.85.27 "grep -n 'then: function' /home/phyziolinegit/htdocs/phyzioline.com/bootstrap/app.php"

Write-Host "---------------------------------------------------"
Write-Host "If you see 'then: function' above, the fix IS on the server."
Write-Host "If you DON'T see it, the file didn't update correctly."
