# Download translations from Weblate Follow these instructions to create your configuration file:
# https://docs.weblate.org/en/latest/wlc.html#files Before executing this script install Python 3 and execute this
# command to install wlc (Weblate Client): pip install wlc

print('Loading modules...')
import ntpath
import os
try:
    import wlc.config
except ImportError:
    print('ERROR! You need to install the Weblate client: pip install wlc')
    exit()
print("Loading Weblate config...")
config = wlc.config.WeblateConfig('weblate')
try:
    config.load('.weblate')
except FileNotFoundError:
    print('ERROR! You need to create your configuration file for Weblate. See these instructions: '
          'https://docs.weblate.org/en/latest/wlc.html#files')
    exit()

w = wlc.Weblate(config=config)
print('Getting available translations...')
component = w.get_component(config.get('weblate', 'project') + '/' + config.get('weblate', 'component'))
for language in component.list():
    if language.language.code == config.get('weblate', 'source_language'):
        continue
    print('Downloading translation for language', language.language.code, '...')
    po = language.download()
    filename = ntpath.basename(language.filename)
    os.makedirs(language.filename.replace(filename, ''), exist_ok=True)
    print('Saving translation for language', language.language.code, '...')
    f = open(language.filename, 'w')
    f.write(po.decode('UTF-8'))
    f.close()
    print('Translation file for', language.language.code, 'saved!')
