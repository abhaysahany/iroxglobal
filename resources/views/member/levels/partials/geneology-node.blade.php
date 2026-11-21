@foreach($nodes as $node)
@php
    $member = $node['member'];
    $children = $node['children'] ?? [];
    $level = $level ?? 1;
@endphp

<div class="geneology-branch geneology-level-{{ $level }}">
    <div class="geneology-organic-link"></div>

    <a href="{{ route('member.referral.genealogy', ['memberid' => $member->memberid ?? '']) }}"
       class="geneology-member-card geneology-node-card geneology-hover-target"
       data-memberid="{{ $member->memberid ?? 'N/A' }}"
       data-name="{{ $member->name ?? 'N/A' }}"
       data-email="{{ $member->email ?? 'N/A' }}"
       data-sponsorid="{{ $member->sponsorid ?? 'N/A' }}"
       data-rank="{{ $member->rank ?? $member->package ?? 'Basic' }}"
       data-status="{{ $member->status ?? 'Inactive' }}">

        <div class="geneology-member-avatar">
            <i class="fa-solid fa-user"></i>
        </div>

        <div class="geneology-member-info">
            <h4>{{ $member->memberid ?? 'N/A' }}</h4>
            <p>{{ $member->name ?? 'N/A' }}</p>
            <span>Sponsor: {{ $member->sponsorid ?? 'N/A' }}</span>
            <span>Rank: {{ $member->rank ?? $member->package ?? 'Basic' }}</span>
            <span class="geneology-member-status">Status: {{ $member->status ?? 'Inactive' }}</span>
        </div>

        @if(count($children) > 0)
            <em class="geneology-drill-btn">
                <i class="fa-solid fa-sitemap"></i> Drill Down
            </em>
        @endif
    </a>

    @if(count($children) > 0)
        <div class="geneology-children">
            @include('member.levels.partials.geneology-node', [
                'nodes' => $children,
                'level' => $level + 1
            ])
        </div>
    @endif
</div>
@endforeach
