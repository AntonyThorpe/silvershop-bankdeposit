<% include SilverShop\Includes\AccountNavigation %>
<div id="Account" class="typography">
	<% if Order %>
		<% with Order %>

            <!-- this is the test theme -->
			<!-- Order_BankDepositNeededStatement AccountPage-->
			<% if $Status == "Unpaid" %>
				<% include SilverShop\Model\Order_BankDepositNeededStatement %>
			<% end_if %>
            <!-- end Order_BankDepositNeededStatement AccountPage-->

			<h2><%t SilverShop\\Model\\Order.OrderHeadline "Order #{OrderNo} {OrderDate}" OrderNo=$Reference OrderDate=$Created.Nice %></h2>
		<% end_with %>
	<% end_if %>
	<% if Message %>
		<p class="message $MessageType">$Message</p>
	<% end_if %>
	<% if Order %>
		<% with Order %>
			<% include SilverShop\Model\Order %>
		<% end_with %>
        $Form
	<% end_if %>
</div>
