This folder is intended to hold a local copy of Bootstrap's SCSS so you can run the Sass migrator and make compatibility fixes locally.

Steps to populate and migrate the SCSS (PowerShell):

1. Copy Bootstrap SCSS into this folder:
   mkdir resources\sass\vendor\bootstrap
   robocopy node_modules\bootstrap\scss resources\sass\vendor\bootstrap /E

2. (Optional) Inspect the copied files in resources/sass/vendor/bootstrap.

3. Run the official Sass migrator (if available) against the copied files. Example:
   npx @sass/migrator import resources/sass/vendor/bootstrap
   npx @sass/migrator color resources/sass/vendor/bootstrap
   npx @sass/migrator unit resources/sass/vendor/bootstrap

4. Adjust any imports in the copied files (migrator attempts to convert @import -> @use/@forward but you may need to tweak namespaces).

5. Compile assets:
   npm run dev

Notes:
- Keep this folder under version control if you want to maintain local patches, but remember it increases maintenance cost when upgrading Bootstrap.
- I recommend running the migrator in a separate branch and reviewing changes before committing.
