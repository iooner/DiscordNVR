# DiscordNVR
The poor guy NVR using Discord as storage

Camera detection (MP4 clip) -- FTP --> Shared hosting (With PHP, Cron and CUrl) -- Webhook --> Discord


The file is deleted after upload.

Discord only allows 50MB via the webhook, the error is not processed.

The script uploads one file at a time, which is enough for my case.
