<% require themedCSS(account,shop) %>
<% include AccountNavigation %>
<div class="typography">
	<% if Order %>
		<% with Order %>
			
			<%-- Silvershop Bank Deposit --%>
			<% if $Status == "Unpaid" %>
				<% include Order_BankDepositNeededStatement %>
			<% end_if %>

			<h2><% _t('AccountPage.ss.ORDER','Order') %> $Reference ($Created.Long)</h2>
		<% end_with %>
	<% end_if %>
	<% if Message %>
		<p class="message $MessageType">$Message</p>
	<% end_if %>
	<% if Order %>
		<% with Order %>				
			<% include Order %>
		<% end_with %>
		
		<%-- SS Shop Bank Deposit --%>
		<% if $Order.Status == "Unpaid" %>
			$ActionsForm
		<% end_if %>

	<% end_if %>
</div>