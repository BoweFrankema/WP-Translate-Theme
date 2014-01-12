<?php
gp_title( sprintf( __('%s &lt; GlotPress'), esc_html( $project->name ) ) );
gp_breadcrumb_project( $project );

wp_enqueue_script( 'common' );
wp_enqueue_script('tablesorter');

$edit_link = gp_link_project_edit_get( $project, __('Edit'), array( 'class' => 'btn btn-xs btn-primary' ) );
$parity = gp_parity_factory();

if ( $project->active ) add_filter( 'gp_breadcrumb', lambda( '$s', '$s . "<span class=\\"active label label-success\\">' . __('Active') . '</span>"' ) );

gp_tmpl_header();
?>

		<h2><?php echo esc_html( $project->name ); ?> <?php echo $edit_link; ?></h2>

		<p class="description">
			<?php echo $project->description; ?>
		</p>

		<?php if ( $can_write ): ?>

		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				<?php _e('Project actions'); ?> <span class="caret"></span>
			</button>

			<?php GP_Bootstrap_Theme_Hacks::gp_project_actions( $project, $translation_sets ); ?>
		</div>

		<?php endif; ?>

		<div id="project" class="row">

			<?php if ( $translation_sets ): ?>
			<div id="translation-sets" class="<?php if ( $sub_projects ) { echo 'col-md-8 col-md-push-4'; } else { echo 'col-md-12'; } ?>">
				<h3><? _e('Translations');?></h3>
				<table class="translation-sets tablesorter table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th><?php _e( 'Language' ); ?></th>
							<th><?php echo _x( '%', 'language translation percent header' ); ?></th>
							<th><?php _e( 'Translated' ); ?></th>
							<th><?php _e( 'Untranslated' ); ?></th>
							<th><?php _e( 'Waiting' ); ?></th>
							<?php if ( has_action( 'project_template_translation_set_extra' ) ) : ?>
							<th class="extra"><?php _e( 'Extra' ); ?></th>
							<?php endif; ?>
						</tr>
					</thead>

					<tbody>
					<?php foreach( $translation_sets as $set ): ?>
						<tr class="<?php echo $parity(); ?>">
							<td>
								<strong><?php gp_link( gp_url_project( $project, gp_url_join( $set->locale, $set->slug ) ), $set->name_with_locale() ); ?></strong>
								<?php if ( $set->current_count && $set->current_count >= $set->all_count * 0.9 ):
										$percent = floor( $set->current_count / $set->all_count * 100 );
								?>
									<span class="label label-success"><?php echo $percent; ?>%</span>
								<?php endif; ?>
							</td>
							<td class="stats percent"><?php echo $set->percent_translated; ?></td>
							<td class="stats translated" title="translated"><?php gp_link( gp_url_project( $project, gp_url_join( $set->locale, $set->slug ),
										array('filters[translated]' => 'yes', 'filters[status]' => 'current') ), $set->current_count );; ?></td>
							<td class="stats untranslated" title="untranslated"><?php gp_link( gp_url_project( $project, gp_url_join( $set->locale, $set->slug ),
										array('filters[status]' => 'untranslated' ) ), $set->untranslated_count ); ?></td>
							<td class="stats waiting"><?php gp_link( gp_url_project( $project, gp_url_join( $set->locale, $set->slug ),
										array('filters[translated]' => 'yes', 'filters[status]' => 'waiting') ), $set->waiting_count ); ?></td>
							<?php if ( has_action( 'project_template_translation_set_extra' ) ) : ?>
							<td class="extra">
								<?php do_action( 'project_template_translation_set_extra', $set, $project ); ?>
							</td>
							<?php endif; ?>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php elseif ( ! $sub_projects ): ?>
			<div class="col-md-12">
				<p><?php _e('There are no translations of this project.'); ?></p>
			</div>
			<?php endif; ?>


			<?php if ($sub_projects): ?>
			<div id="sub-projects" class="<?php if ( $translation_sets ) { echo 'col-md-4 col-md-pull-8'; } else { echo 'col-md-4'; } ?>">
				<h3><?php _e('Sub-projects'); ?></h3>

				<dl>
				<?php foreach ( $sub_projects as $sub_project ): ?>
					<dt>
						<?php gp_link_project( $sub_project, esc_html( $sub_project->name ) ); ?>
						<?php gp_link_project_edit( $sub_project, null, array( 'class' => 'label label-primary' ) ); ?>
						<?php if ( $sub_project->active ) echo "<span class='active label label-success'>" . __('Active') . "</span>"; ?>
					</dt>
					<dd>
						<?php echo esc_html( gp_html_excerpt( $sub_project->description, 111 ) ); ?>
					</dd>
				<?php endforeach; ?>
				</dl>
			</div>
			<?php endif; ?>

		</div>

		<div class="clear"></div>


		<script type="text/javascript" charset="utf-8">
			$gp.showhide('a.personal-options', 'div.personal-options', {
				show_text: '<?php _e('Personal project options &darr;'); ?>',
				hide_text: '<?php _e('Personal project options &uarr;'); ?>',
				focus: '#source-url-template',
				group: 'personal'
			});
			$('div.personal-options').hide();

			$(document).ready(function() {
				$(".translation-sets").tablesorter({
					headers: {
						0: {
							sorter: 'text'
						}
					}
				});
			});
		</script>

<?php gp_tmpl_footer();
