<% include Order_Address %>
<% include Order_Content %>
<% if Total %>
	<% if Payments %>
		<% include Order_Payments %>
	<% end_if %>
	<table id="OutstandingTable" class="infotable" cellpadding="0" cellspacing="0" >
		<tbody>
			<tr class="gap summary" id="Outstanding">
				<th colspan="4" scope="row" class="threeColHeader"><strong><% _t("TOTALOUTSTANDING","Total outstanding") %></strong></th>
				<td class="right">
					<strong>

						<%-- Silvershop Bank Deposit --%>
						<% if $Status == "Unpaid" %>
							$TotalOutstanding.Nice
						<% else %>
							<% if $Status == "MemberCancelled" || $Status == "AdminCancelled" %>
								<% _t("ORDERCANCELLED","Order Cancelled") %>
							<% else %>
								<% _t("PAIDMESSAGEORDER","Paid with thanks") %>
							<% end_if %>
						<% end_if %>

					</strong>
				</td>
			</tr>
		</tbody>
	</table>
<% end_if %>

<% if Notes %>
	<table id="NotesTable" class="infotable">
		<thead>
			<tr>
				<th><% _t("ORDERNOTES","Notes") %></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>$Notes</td>
			</tr>
		</tbody>
	</table>
<% end_if %>

<%-- Silvershop Bank Deposit --%>
<% if Total %>
	<% if TotalOutstanding %>
		<% if Payments %>
			<% loop Payments.last %>
				<% if GatewayTitle == "Bank Deposit" %>
					<% include Order_BankDeposit %>
				<% end_if %>
			<% end_loop %>
		<% end_if %>
	<% end_if %>
<% end_if %>
