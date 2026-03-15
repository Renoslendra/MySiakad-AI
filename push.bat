@echo off
echo [MySiakad-AI] Staging changes...
git add .
echo [MySiakad-AI] Committing changes...
git commit -m "MySiakad-AI: %date% %time% automated update"
echo [MySiakad-AI] Pushing to GitHub (Railway will deploy automatically)...
git push origin main
echo [MySiakad-AI] Done! Your website will be live on Railway shortly.
pause


