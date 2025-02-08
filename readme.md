# Joomet

**Joomla! Language File checker & Translator Component**

## Requirements

- Joomla! 5.x
- CodeMirror Editor (Plugin) installed and enabled*
- PHP 8.3.x

(*) does not need to be the default editor

## Project State

| Feature                                 | Status |                    |
|-----------------------------------------|--------|--------------------|
| Component structure                     | done   | :white_check_mark: |
| Upload language files                   | done   | :white_check_mark: |
| Select uploaded language files          | done   | :white_check_mark: |
| Manage uploaded language files          | done   | :white_check_mark: |
| Checker Service                         | done   | :white_check_mark: |
| Checker Report                          | done   | :white_check_mark: |
| Select locally installed language files | done   | :white_check_mark: |
| Translation Service                     | done   | :white_check_mark: |


## Deployment

1. Run `composer post-install-deploy` to install DeepL package for deployment
2. Run `composer deploy-zip` to create a ZIP File ready for installation
3. Install the created ZIP like any other extension

##