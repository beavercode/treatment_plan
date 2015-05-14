#require 'compass/import-once/activate' ????
# Require any additional compass plugins here.

# Autoprefixer @ https://github.com/postcss/autoprefixer
require 'autoprefixer-rails'
on_stylesheet_saved do |file|
  css = File.read(file)
  map = file + '.map'
  if File.exists? map
    result = AutoprefixerRails.process(css,
      from: file,
      to:   file,
      map:  { prev: File.read(map), inline: false })
    File.open(file, 'w') { |io| io << result.css }
    File.open(map,  'w') { |io| io << result.map }
  else
    File.open(file, 'w') { |io| io << AutoprefixerRails.process(css) }
  end
end
# /Autoprefixer

#Folder settings
#http_path = "/"
#relative_assets = true         #because we're not working from the root

css_dir = "css"          #where the CSS will saved
javascripts_dir = "js"   #where the JS will saved
sass_dir = "sass"        #where our .sass files are
images_dir = "img"       #the folder with your images
sourcemap = false

# You can select your preferred output style here (can be overridden via the command line):
# opt= :expanded or :nested or :compact or :compressed
output_style = :expanded

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = false

# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
preferred_syntax = :scss
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass