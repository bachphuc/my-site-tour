<upgrade>
	<phpfox_update_user_group_settings>
		<setting>
			<is_admin_setting>0</is_admin_setting>
			<module_id>comment</module_id>
			<type>boolean</type>
			<admin>0</admin>
			<user>0</user>
			<guest>0</guest>
			<staff>0</staff>
			<module>comment</module>
			<ordering>0</ordering>
			<value>wysiwyg_on_comments</value>
		</setting>
	</phpfox_update_user_group_settings>
	<hooks>
		<hook>
			<module_id>comment</module_id>
			<hook_type>component</hook_type>
			<module>comment</module>
			<call_name>comment.component_block_share_clean</call_name>
			<added>1339076699</added>
			<version_id>3.3.0beta1</version_id>
			<value />
		</hook>
	</hooks>
</upgrade>