<%-- Bank Deposit table for Order/Invoice on Account page --%>
<table id="BankDepositTable" class="infotable" cellspacing="0" cellpadding="0" summary="<% _t('BankDepositInformation','Bank Deposit Information') %>" >
	<thead>
		<tr class="gap mainHeader">
			<th>
				<% _t("BankDepositHeading","Bank Deposit") %>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<% with SiteConfig %>
					<% if $BankAccountNumber %>
						<% _t("BankAccountNumberTitle", "Bank Account Number: ") %>
									$BankAccountNumber
						<br/>
					<% end_if %>
					<% if $BankAccountDetails %>
						$BankAccountDetails
						<br/>
					<% end_if %>
					<% if $BankAccountInvoiceMessage %>
						$BankAccountInvoiceMessage
					<% end_if %>
				<% end_with %>
			</td>
		</tr>
	</tbody>
</table>
