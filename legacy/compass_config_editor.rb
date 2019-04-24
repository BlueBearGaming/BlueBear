# Require any additional compass plugins here.

# Set this to the root of your project when deployed:
http_path = "web"
css_dir = "src/BlueBear/EditorBundle/Resources/public/css"
sass_dir = "src/BlueBear/EditorBundle/Resources/sass"
images_dir = "src/BlueBear/EditorBundle/Resources/images"
javascripts_dir = "src/BlueBear/EditorBundle/Resources/public/js"

relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = false
environment = :dev

if environment == :production
  output_style = :compressed
else
  output_style = :expanded
  sass_options = { :debug_info => true }
end