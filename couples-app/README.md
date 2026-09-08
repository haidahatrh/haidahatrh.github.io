# Us — a private couples app

A 5-tab PWA for you and your boyfriend. Runs on XAMPP.

## Tabs
1. **Today** — day counter, daily mood, question of the day, anniversary
2. **Games** — Would You Rather, Deep Questions, Playful Dares, How Well Do You Know Me
3. **Plans** — upcoming dates & events
4. **Feed** — Instagram-style shared feed (photos + captions + likes + comments)
5. **Finance** — hangout expense breakdown, auto-split, running balance

## Run locally
1. Make sure XAMPP is running (Apache).
2. Open http://localhost/project/couples-app/
3. Pick "Me" or "Boyfriend" on first launch.

## Use on your phones (same WiFi)
1. Find your PC's LAN IP (Windows: `ipconfig` → IPv4).
2. On your phone browser, visit `http://<pc-ip>/project/couples-app/`
3. **iOS Safari**: Share → Add to Home Screen.
4. **Android Chrome**: menu → Install app.

Both phones point to the same PC, so all data syncs.

## Deploy to the internet (so it works anywhere)
Upload the whole `couples-app/` folder to any PHP host (Hostinger, 000webhost, InfinityFree, your own VPS). No database needed — everything is JSON files in `/data/`.

## Data storage
- `data/today.json` — day counter, moods, QoD
- `data/plans.json` — plans array
- `data/feed.json` — posts array
- `data/finance.json` — hangouts array
- `uploads/` — feed images

## Reset data
Delete files inside `data/` and `uploads/`.
