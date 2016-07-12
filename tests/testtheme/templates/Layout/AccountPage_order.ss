<% require themedCSS(account,shop) %>
<% include AccountNavigation %>
<div class="typography">
	<% if Order %>
		<% with Order %>
			
			<%-- Silvershop Bank Deposit --%>
			<% if $Status == "Unpaid" %>
				<% include Order_BankDepositNeededStatement %>
			<% end_if %>

			<h2><%t Order.OrderHeadline "Order #{OrderNo} {OrderDate}" OrderNo=$Reference OrderDate=$Created.Nice %></h2>
		<% end_with %>
	<% end_if %>
	<% if Message %>
		<p class="message $MessageType">$Message</p>
	<% end_if %>
	<% if Order %>
		<% with Order %>				
			<% include Order %>
		<% end_with %>
		
		<%-- Silvershop Bank Deposit --%>
		<% if $Order.Status == "Unpaid" %>
			$ActionsForm
		<% end_if %>

	<% end_if %>
</div>