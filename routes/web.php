<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Member\ActivationController;
use App\Http\Controllers\Member\MemberAuthController;
use App\Http\Controllers\Member\MemberDashboardController;
use App\Http\Controllers\Member\MemberFundController;
use App\Http\Controllers\Member\MemberIncomeController;
use App\Http\Controllers\Member\MemberProfileController;
use App\Http\Controllers\Member\MemberReferralController;
use App\Http\Controllers\Member\MemberSupportController;
use App\Http\Controllers\Member\MemberWalletController;
use App\Http\Controllers\TempController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'index']);

Route::get('/login', [MemberAuthController::class, 'login'])->name('member.login');
Route::post('/authenticate', [MemberAuthController::class, 'loginSubmit'])->name('member.login.submit');
Route::get('/signup/{id?}', [MemberAuthController::class, 'signup'])->name('member.signup');
Route::post('/signup', [MemberAuthController::class, 'signupSubmit'])->name('member.signup.submit');
Route::post('/check-sponsor', [MemberAuthController::class, 'checkSponsor'])->name('member.check-sponsor');

Route::get('/forgot-password', [MemberAuthController::class, 'forgotPassword'])->name('member.forgot-password');
Route::post('/forgot-password', [MemberAuthController::class, 'sendForgotPassword'])->name('member.forgot-password.submit');
Route::get('/member/account-created', [MemberAuthController::class, 'accountCreated']);
Route::get('/verify-admin/{memberid}/{code}', [MemberAuthController::class, 'verifyAdmin']);

Route::prefix('member')->name('member.')->group(function () {
    Route::middleware('member.auth')->group(function () {
        Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [MemberProfileController::class, 'index'])->name('profile');
        Route::get('/profile/summary', [MemberProfileController::class, 'summary'])->name('profile.summary');
        Route::get('/profile/edit-profile', [MemberProfileController::class, 'editProfile'])->name('profile.edit-profile');
        Route::post('/profile/edit-profile', [MemberProfileController::class, 'insertPdata'])->name('profile.edit-profile.update');
        Route::get('/profile/change-password', [MemberProfileController::class, 'changePassword'])->name('profile.change-password');
        Route::post('/profile/change-password', [MemberProfileController::class, 'updatePassword'])->name('profile.change-password.update');
        Route::post('/profile/change-transaction-password', [MemberProfileController::class, 'txnpassChange'])->name('profile.change-transaction-password.update');
        Route::get('/profile/wallet-address', [MemberProfileController::class, 'walletAddress'])->name('profile.wallet-address');
        Route::post('/profile/wallet-address', [MemberProfileController::class, 'insertWalletAddress'])->name('profile.wallet-address.update');
        Route::post('/profile/wallet-address/send-otp', [MemberProfileController::class, 'sendWalletAddressOtp'])->name('profile.wallet-address.send-otp');

        // KYC routes
        Route::get('/profile/kyc', [MemberProfileController::class, 'kyc'])->name('profile.kyc');
        Route::post('updateKyc', [MemberProfileController::class, 'updateKyc'])->name('updateKyc');

        Route::post('/profile/update', [MemberProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/password', [MemberProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/avatar', [MemberProfileController::class, 'updateAvatar'])->name('profile.avatar');
        Route::get('/import-fund', [MemberFundController::class, 'importFund'])->name('import-fund');
        Route::post('addFund', [MemberFundController::class, 'addFund'])->name('addFund');
        Route::get('/add-fund/history', [MemberFundController::class, 'importFundHis'])->name('add-fund.history');

        // incomes
        Route::get('/incomes/daily-income', [MemberIncomeController::class, 'daily'])->name('incomes.daily');
        Route::get('/incomes/referral-income', [MemberIncomeController::class, 'referral'])->name('incomes.referral');
        Route::get('/incomes/rank-income', [MemberIncomeController::class, 'rank'])->name('incomes.rank');
        Route::get('/incomes/global-rec', [MemberIncomeController::class, 'globalRec'])->name('incomes.globalRec');
        Route::get('/incomes/global-rec-star', [MemberIncomeController::class, 'globalRecStar'])->name('incomes.globalRecStar');

        Route::get('/wallet/income-wallet', [MemberWalletController::class, 'mainWallets'])->name('wallet.income');
        Route::get('/wallet/withdrawal-history', [MemberWalletController::class, 'initWithdrawal'])->name('wallet.withdrawal-history');
        Route::post('/wallet/income-withdraw', [MemberWalletController::class, 'initWithdrawal'])->name('wallet.income-withdraw');
        Route::get('/wallet/payout-data', [MemberWalletController::class, 'payoutDate'])->name('wallet.payout-data');
        Route::get('/wallet/all-transactions', [MemberWalletController::class, 'allTransactions'])->name('wallet.all-transactions');
        Route::get('/wallet/to-p2p', [MemberWalletController::class, 'walletToP2P'])->name('wallet.to-p2p');
        Route::post('/wallet/to-p2p/transfer', [MemberWalletController::class, 'walletP2pTransfer'])->name('wallet.to-p2p.submit');
        Route::get('/wallet/to-p2p-history', [MemberWalletController::class, 'walletP2Phistory'])->name('wallet.to-p2p-history');
        Route::get('/p2p/wallet', [MemberWalletController::class, 'fundTrans'])->name('p2p.wallet');
        Route::post('/p2p/wallet', [MemberWalletController::class, 'fundTrans'])->name('p2p.wallet.submit');
        Route::get('/p2p/wallet-history', [MemberWalletController::class, 'WalletHistory'])->name('p2p.wallet-history');

        Route::get('/referral/direct-members', [MemberReferralController::class, 'directMembers'])->name('referral.direct-members');
        Route::post('/referral/assign-direct', [MemberReferralController::class, 'assignDirectMember'])->name('referral.assign-direct');

        Route::get('/referral/genealogy', [MemberReferralController::class, 'geneology'])->name('referral.genealogy');
        Route::post('/referral/search-genealogy', [MemberReferralController::class, 'searchGenelogy'])->name('referral.search-genealogy');
        Route::get('/referral/view-genealogy/{did}', [MemberReferralController::class, 'ViewGeneology'])->name('referral.view-genealogy');
        Route::get('/team/geneology', [MemberReferralController::class, 'geneology'])->name('team.genealogy');

        Route::get('/referral/level-members', [MemberReferralController::class, 'lelMemTable'])->name('referral.level-members');
        Route::get('/referral/level-members/{level}', [MemberReferralController::class, 'lelMemDetails'])->name('referral.level-members.details');

        Route::get('/support/create-ticket', [MemberSupportController::class, 'createTick'])->name('support.create-ticket');
        Route::post('/support/create-ticket', [MemberSupportController::class, 'createTicket'])->name('support.create-ticket.submit');
        Route::get('/support/tickets', [MemberSupportController::class, 'supportTickets'])->name('support.tickets');
        Route::get('/support/tickets/{tid}', [MemberSupportController::class, 'ViewsupportTickets'])->name('support.view-support-tickets');
        Route::post('/support/tickets/reply', [MemberSupportController::class, 'MbReplyTicket'])->name('support.tickets.reply');

        Route::get('/activation', [ActivationController::class, 'index'])->name('activation.index');
        Route::post('/activation/buy', [ActivationController::class, 'buy'])->name('activation.buy');

        Route::post('/logout', [MemberAuthController::class, 'logout'])->name('logout');
        Route::post('/getMember', [MemberAuthController::class, 'getMember']);
    });
});

// Route::get('generateMemberWallets', [TempController::class, 'generateMemberWallets']);

Route::fallback(function () {
    return view('member.error404');
});

Route::get('/create-fake-chain/{root_memberid?}', [MemberReferralController::class, 'createFakeChain'])
    ->name('create.fake.chain');

// Route::get('updateWeeklyCount', [TempController::class, 'updateWeeklyCount']);
// Route::get('poolIncomeDis', [TempController::class, 'poolIncomeDis']);
// Route::get('rankGen', [TempController::class, 'rankGen']);
// Route::get('rankIncomeDis', [TempController::class, 'rankIncomeDis']);
// Route::get('renewDate', [TempController::class, 'renewDate']);
Route::get('levelIncomeDis', [TempController::class, 'levelIncomeDis']);
